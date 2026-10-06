<?php

namespace App\Livewire\Pengajuan;

use App\Models\ListProtokol as ListProtokolModel;
use App\Models\SuratPengajuan;
use App\Services\ListProtokolService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;

class ListProtokol extends Component
{
    use WithFileUploads;

    public SuratPengajuan $suratPengajuan;

    public bool $showModal = false;

    public string $search = '';

    public string $nomor_protokol = '';

    public string $judul = '';

    public string $peneliti_utama = '';

    public string $institusi_asal = '';

    public string $review_type = 'expedited';

    public string $tanggal_pengajuan = '';

    public string $status_protokol = 'approved';

    /**
     * Upload document state
     */
    public $dokumen = null;

    public ?string $existingDokumenNama = null;

    public ?string $existingDokumenPath = null;

    public ?int $existingDokumenUkuran = null;

    public bool $hapusDokumenLama = false;

    public ?int $editingId = null;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'nomor_protokol' => ['required', 'string', 'max:100'],
            'judul' => ['required', 'string', 'max:255'],
            'peneliti_utama' => ['required', 'string', 'max:255'],
            'institusi_asal' => ['nullable', 'string', 'max:255'],
            'review_type' => ['required', 'string'],
            'tanggal_pengajuan' => ['nullable', 'date'],
            'status_protokol' => ['required', 'string'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip,jpg,jpeg,png', 'max:20480'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'dokumen.mimes' => 'Format berkas dokumen harus PDF, DOC, DOCX, XLS, XLSX, ZIP, atau Gambar (JPG, PNG).',
            'dokumen.max' => 'Ukuran berkas dokumen tidak boleh melebihi 20 MB.',
        ];
    }

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        if (auth()->user()?->isAnggota()) {
            abort(403, 'Anggota KEPK tidak memiliki hak akses ke Halaman List Protokol Riset.');
        }

        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load('listProtokol');
        $this->tanggal_pengajuan = now()->toDateString();
    }

    public function tambahProtokol(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function tutupModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function hapusDokumenSaatIni(): void
    {
        $this->hapusDokumenLama = true;
        $this->existingDokumenNama = null;
        $this->existingDokumenPath = null;
        $this->existingDokumenUkuran = null;
        $this->dokumen = null;
    }

    public function batalUploadDokumen(): void
    {
        $this->dokumen = null;
    }

    public function simpan(ListProtokolService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $validated = $this->validate();

        $payload = [
            'nomor_protokol' => $validated['nomor_protokol'],
            'judul' => $validated['judul'],
            'peneliti_utama' => $validated['peneliti_utama'],
            'institusi_asal' => $validated['institusi_asal'],
            'review_type' => $validated['review_type'],
            'tanggal_pengajuan' => $validated['tanggal_pengajuan'],
            'status' => $validated['status_protokol'],
        ];

        if ($this->dokumen instanceof UploadedFile) {
            $filename = $this->dokumen->getClientOriginalName();
            $path = $this->dokumen->store("protokol/{$this->suratPengajuan->id}", 'public');
            $size = $this->dokumen->getSize() ?: 0;

            $payload['dokumen_path'] = $path;
            $payload['dokumen_nama'] = $filename;
            $payload['dokumen_ukuran'] = $size;
        } elseif ($this->editingId && $this->hapusDokumenLama) {
            $payload['dokumen_path'] = null;
            $payload['dokumen_nama'] = null;
            $payload['dokumen_ukuran'] = null;
        }

        if ($this->editingId) {
            $protokol = ListProtokolModel::findOrFail($this->editingId);
            $service->update($protokol, $payload);
            session()->flash('status', 'Data protokol riset berhasil diperbarui.');
        } else {
            $service->create($this->suratPengajuan, $payload);
            session()->flash('status', 'Protokol riset baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->suratPengajuan->refresh();
    }

    public function edit(int $id): void
    {
        $protokol = ListProtokolModel::findOrFail($id);
        $this->editingId = $protokol->id;
        $this->nomor_protokol = $protokol->nomor_protokol;
        $this->judul = $protokol->judul;
        $this->peneliti_utama = $protokol->peneliti_utama;
        $this->institusi_asal = $protokol->institusi_asal ?? '';
        $this->review_type = $protokol->review_type ?? 'expedited';
        $this->tanggal_pengajuan = $protokol->tanggal_pengajuan?->toDateString() ?? '';
        $this->status_protokol = $protokol->status;

        $this->dokumen = null;
        $this->existingDokumenNama = $protokol->dokumen_nama;
        $this->existingDokumenPath = $protokol->dokumen_path;
        $this->existingDokumenUkuran = $protokol->dokumen_ukuran;
        $this->hapusDokumenLama = false;

        $this->showModal = true;
    }

    public bool $showDeleteModal = false;

    public ?int $selectedDeleteId = null;

    public string $selectedDeleteJudul = '';

    public function konfirmasiHapus(int $id): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $protokol = ListProtokolModel::findOrFail($id);
        $this->selectedDeleteId = $protokol->id;
        $this->selectedDeleteJudul = $protokol->judul;
        $this->showDeleteModal = true;
    }

    public function batalHapus(): void
    {
        $this->showDeleteModal = false;
        $this->selectedDeleteId = null;
        $this->selectedDeleteJudul = '';
    }

    public function eksekusiHapus(ListProtokolService $service): void
    {
        if ($this->selectedDeleteId && $this->suratPengajuan->isEditable()) {
            $protokol = ListProtokolModel::find($this->selectedDeleteId);
            if ($protokol) {
                $service->delete($protokol);
                $this->suratPengajuan->refresh();
                $this->suratPengajuan->unsetRelation('listProtokol');
                session()->flash('status', 'Protokol riset berhasil dihapus.');
            }
        }

        $this->batalHapus();
    }

    public function hapus(int $id, ListProtokolService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $protokol = ListProtokolModel::find($id);
        if ($protokol) {
            $service->delete($protokol);
            $this->suratPengajuan->refresh();
            $this->suratPengajuan->unsetRelation('listProtokol');
            session()->flash('status', 'Protokol riset berhasil dihapus.');
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId',
            'nomor_protokol',
            'judul',
            'peneliti_utama',
            'institusi_asal',
            'dokumen',
            'existingDokumenNama',
            'existingDokumenPath',
            'existingDokumenUkuran',
            'hapusDokumenLama',
        ]);
        $this->review_type = 'expedited';
        $this->tanggal_pengajuan = now()->toDateString();
        $this->status_protokol = 'approved';
        $this->resetErrorBag();
    }

    public function render(): View
    {
        $query = $this->suratPengajuan->listProtokol()->latest();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nomor_protokol', 'like', '%'.$this->search.'%')
                    ->orWhere('judul', 'like', '%'.$this->search.'%')
                    ->orWhere('peneliti_utama', 'like', '%'.$this->search.'%')
                    ->orWhere('institusi_asal', 'like', '%'.$this->search.'%')
                    ->orWhere('dokumen_nama', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.pengajuan.list-protokol', [
            'protokolList' => $query->get(),
        ])->layout('layouts.app');
    }
}

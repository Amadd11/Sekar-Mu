<?php

namespace App\Livewire\Pengajuan;

use App\Models\Kepk;
use App\Models\SuratPengajuan;
use App\Services\PengajuanService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    // Modal Create State
    public bool $showCreateModal = false;
    public ?int $kepk_id = null;
    public string $nomor_berkas = '';
    public string $nama_institusi = '';
    public string $singkatan = '';
    public string $alamat = '';
    public string $kota = '';
    public string $telepon = '';
    public string $email = '';
    public string $deskripsi = '';
    public string $visi = '';
    public string $misi = '';

    public function mount(): void
    {
        $this->authorize('viewAny', SuratPengajuan::class);

        if (request()->query('create') == '1') {
            $this->bukaModalCreate();
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'kepk_id' => ['required', 'exists:kepk,id'],
            'nomor_berkas' => ['nullable', 'string', 'max:100'],
            'nama_institusi' => ['required', 'string', 'max:255'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'visi' => ['nullable', 'string'],
            'misi' => ['nullable', 'string'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->reset(['nomor_berkas', 'nama_institusi', 'singkatan', 'alamat', 'kota', 'telepon', 'email', 'deskripsi', 'visi', 'misi']);
        
        $defaultKepk = Kepk::where('status', 'active')->first() ?? Kepk::first();
        if ($defaultKepk) {
            $this->kepk_id = $defaultKepk->id;
        }

        $this->showCreateModal = true;
    }

    public function tutupModalCreate(): void
    {
        $this->showCreateModal = false;
    }

    public function simpanPengajuan(PengajuanService $service)
    {
        $validated = $this->validate();

        $surat = $service->createDraft(auth()->user(), $validated);

        session()->flash('status', 'Draft surat pengajuan berhasil dibuat! Silakan lanjutkan pengisian berkas evaluasi diri.');

        return $this->redirect(route('pengajuan.show', $surat), navigate: true);
    }

    // Modal Delete State
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public string $deletingNomor = '';

    public function konfirmasiHapus(int $id): void
    {
        $surat = SuratPengajuan::findOrFail($id);
        $this->authorize('delete', $surat);

        $this->deletingId = $surat->id;
        $this->deletingNomor = $surat->formatted_id;
        $this->showDeleteModal = true;
    }

    public function batalHapus(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingNomor = '';
    }

    public function hapusPengajuan(PengajuanService $service): void
    {
        if (! $this->deletingId) {
            return;
        }

        $surat = SuratPengajuan::findOrFail($this->deletingId);
        $this->authorize('delete', $surat);

        $formattedId = $surat->formatted_id;
        $service->delete($surat);

        $this->batalHapus();

        session()->flash('status', "Berkas pengajuan No. {$formattedId} berhasil dihapus secara permanen.");
    }

    public function render(): View
    {
        $user = auth()->user();

        $query = SuratPengajuan::query()
            ->with(['kepk.institusi', 'formulirAplikasi', 'penilai', 'penilaianEtik']);

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota()) {
            // Admin, Ketua KEPK, dan Anggota KEPK dapat melihat dan berkolaborasi pada berkas pengajuan
        } elseif ($user->isAsessor()) {
            $query->whereHas('penilai', fn ($q) => $q->where('user_id', $user->id));
        } else {
            $query->where('user_id', $user->id);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('formulirAplikasi', function ($sub) {
                    $sub->where('nama_institusi', 'like', "%{$this->search}%")
                        ->orWhere('kota', 'like', "%{$this->search}%");
                })->orWhere('id', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.pengajuan.index', [
            'pengajuanList' => $query->latest()->paginate(10),
            'daftarKepk' => Kepk::with('institusi')->where('status', 'active')->get(),
        ])->layout('layouts.app');
    }
}

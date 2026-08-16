<?php

namespace App\Livewire\Pengajuan;

use App\Models\ListProtokol as ListProtokolModel;
use App\Models\SuratPengajuan;
use App\Services\ListProtokolService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListProtokol extends Component
{
    public SuratPengajuan $suratPengajuan;

    public string $nomor_protokol = '';
    public string $judul = '';
    public string $peneliti_utama = '';
    public string $tanggal_pengajuan = '';
    public string $status_protokol = 'draft';

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
            'tanggal_pengajuan' => ['nullable', 'date'],
            'status_protokol' => ['required', 'string'],
        ];
    }

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load('listProtokol');
        $this->tanggal_pengajuan = now()->toDateString();
    }

    public function simpan(ListProtokolService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $validated = $this->validate();

        if ($this->editingId) {
            $protokol = ListProtokolModel::findOrFail($this->editingId);
            $service->update($protokol, [
                'nomor_protokol' => $validated['nomor_protokol'],
                'judul' => $validated['judul'],
                'peneliti_utama' => $validated['peneliti_utama'],
                'tanggal_pengajuan' => $validated['tanggal_pengajuan'],
                'status' => $validated['status_protokol'],
            ]);
            session()->flash('status', 'Protokol penelitian berhasil diperbarui.');
        } else {
            $service->create($this->suratPengajuan, [
                'nomor_protokol' => $validated['nomor_protokol'],
                'judul' => $validated['judul'],
                'peneliti_utama' => $validated['peneliti_utama'],
                'tanggal_pengajuan' => $validated['tanggal_pengajuan'],
                'status' => $validated['status_protokol'],
            ]);
            session()->flash('status', 'Protokol penelitian baru berhasil ditambahkan.');
        }

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
        $this->tanggal_pengajuan = $protokol->tanggal_pengajuan?->toDateString() ?? '';
        $this->status_protokol = $protokol->status;
    }

    public function hapus(int $id, ListProtokolService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $protokol = ListProtokolModel::findOrFail($id);
        $service->delete($protokol);
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Protokol penelitian berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'nomor_protokol', 'judul', 'peneliti_utama']);
        $this->tanggal_pengajuan = now()->toDateString();
        $this->status_protokol = 'draft';
    }

    public function render(): View
    {
        return view('livewire.pengajuan.list-protokol', [
            'protokolList' => $this->suratPengajuan->listProtokol()->latest()->get(),
        ])->layout('layouts.app');
    }
}

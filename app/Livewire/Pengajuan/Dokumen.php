<?php

namespace App\Livewire\Pengajuan;

use App\Models\Dokumen as DokumenModel;
use App\Models\SuratPengajuan;
use App\Services\DokumenService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Dokumen extends Component
{
    use WithFileUploads;

    public SuratPengajuan $suratPengajuan;

    public $file;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,zip,jpg,jpeg,png'],
        ];
    }

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load('dokumen.pengunggah');
    }

    public function unggah(DokumenService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $this->validate();

        $service->upload($this->suratPengajuan, auth()->user(), $this->file);

        $this->reset('file');
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Berkas dokumen lampiran berhasil diunggah.');
    }

    public function hapus(int $id, DokumenService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $dok = DokumenModel::findOrFail($id);
        $service->delete($dok);
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Berkas dokumen lampiran berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.pengajuan.dokumen', [
            'daftarDokumen' => $this->suratPengajuan->dokumen()->latest()->get(),
        ])->layout('layouts.app');
    }
}

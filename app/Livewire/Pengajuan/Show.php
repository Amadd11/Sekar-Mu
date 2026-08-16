<?php

namespace App\Livewire\Pengajuan;

use App\Models\SuratPengajuan;
use App\Services\PengajuanService;
use App\Services\PenilaianService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public SuratPengajuan $suratPengajuan;

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load([
            'kepk.institusi',
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'listProtokol',
            'dokumen.pengunggah',
            'penilai',
            'penilaianEtik.penilai',
            'user',
        ]);
    }

    public function ajukanBerkas(PengajuanService $service): void
    {
        $this->authorize('submit', $this->suratPengajuan);

        if (! $this->suratPengajuan->formulirAplikasi || empty($this->suratPengajuan->formulirAplikasi->nama_institusi)) {
            session()->flash('error', 'Mohon lengkapi formulir aplikasi sebelum mengajukan.');

            return;
        }

        $service->submit($this->suratPengajuan);
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Berkas pengajuan etik berhasil diajukan untuk dinilai!');
    }

    public function putuskanStatus(string $status, PenilaianService $service): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang berwenang menetapkan status akhir.');
        }

        $service->finalizeDecision($this->suratPengajuan, $status);
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Status keputusan akhir pengajuan berhasil ditetapkan: ' . SuratPengajuan::statusLabel($status));
    }

    public function hapusDraft(PengajuanService $service)
    {
        $this->authorize('delete', $this->suratPengajuan);

        $service->delete($this->suratPengajuan);

        session()->flash('status', 'Draft surat pengajuan berhasil dihapus.');

        return $this->redirect(route('pengajuan.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pengajuan.show')->layout('layouts.app');
    }
}

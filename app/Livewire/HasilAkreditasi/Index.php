<?php

namespace App\Livewire\HasilAkreditasi;

use App\Models\SuratPengajuan;
use App\Services\ComplianceService;
use App\Services\PengajuanService;
use App\Services\PenilaianService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public SuratPengajuan $suratPengajuan;

    // Modal State
    public bool $showFormulirModal = false;
    public bool $showProfilModal = false;

    // Formulir Form Fields
    public string $nama_institusi = '';
    public string $singkatan = '';
    public string $alamat = '';
    public string $kota = '';
    public string $telepon = '';
    public string $email = '';

    // Profil Form Fields
    public string $deskripsi = '';
    public string $visi = '';
    public string $misi = '';

    public function mount(SuratPengajuan $suratPengajuan)
    {
        $user = auth()->user();
        if ($user && $user->isAsessor() && ! $user->isAdmin()) {
            return redirect()->route('penilaian.show', $suratPengajuan);
        }

        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load([
            'kepk.institusi',
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'listProtokol',
            'dokumen.pengunggah',
            'penilai',
            'penilaianEtik.penilai.roles',
            'penilaianEtik.catatanPenilaian.user',
            'penilaianButirAsesor.butir',
            'correctiveActions.butir',
            'jawabanEvaluasi.butir',
            'user',
        ]);
    }

    public function bukaModalFormulir(): void
    {
        $this->authorize('update', $this->suratPengajuan);
        $this->resetValidation();

        $form = $this->suratPengajuan->formulirAplikasi;
        $this->nama_institusi = $form->nama_institusi ?? '';
        $this->singkatan = $form->singkatan ?? '';
        $this->alamat = $form->alamat ?? '';
        $this->kota = $form->kota ?? '';
        $this->telepon = $form->telepon ?? '';
        $this->email = $form->email ?? '';

        $this->showFormulirModal = true;
    }

    public function simpanFormulir(PengajuanService $service): void
    {
        $this->authorize('update', $this->suratPengajuan);

        $validated = $this->validate([
            'nama_institusi' => ['required', 'string', 'max:255'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
        ]);

        $service->updateFormulirAplikasi($this->suratPengajuan, $validated);
        $this->suratPengajuan->refresh();
        $this->showFormulirModal = false;

        session()->flash('status', 'Identitas institusi pengusul berhasil diperbarui.');
    }

    public function bukaModalProfil(): void
    {
        $this->authorize('update', $this->suratPengajuan);
        $this->resetValidation();

        $profil = $this->suratPengajuan->profilKepk;
        $this->deskripsi = $profil->deskripsi ?? '';
        $this->visi = $profil->visi ?? '';
        $this->misi = $profil->misi ?? '';

        $this->showProfilModal = true;
    }

    public function simpanProfil(PengajuanService $service): void
    {
        $this->authorize('update', $this->suratPengajuan);

        $this->validate([
            'deskripsi' => ['nullable', 'string'],
            'visi' => ['nullable', 'string'],
            'misi' => ['nullable', 'string'],
        ]);

        $service->updateProfilKepk($this->suratPengajuan, [
            'deskripsi' => $this->deskripsi,
            'visi' => $this->visi,
            'misi' => $this->misi,
        ]);

        $this->suratPengajuan->refresh();
        $this->showProfilModal = false;

        session()->flash('status', 'Gambaran umum, visi, dan misi KEPK berhasil diperbarui.');
    }

    public function tutupModal(): void
    {
        $this->showFormulirModal = false;
        $this->showProfilModal = false;
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
        $this->authorize('decide', $this->suratPengajuan);

        $service->finalizeDecision($this->suratPengajuan, $status);
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Status keputusan akhir pengajuan berhasil ditetapkan: ' . $this->suratPengajuan->status_label);
    }

    public bool $showDeleteModal = false;

    public function konfirmasiHapus(): void
    {
        $this->authorize('delete', $this->suratPengajuan);
        $this->showDeleteModal = true;
    }

    public function batalHapus(): void
    {
        $this->showDeleteModal = false;
    }

    public function hapusDraft(PengajuanService $service)
    {
        $this->authorize('delete', $this->suratPengajuan);

        $service->delete($this->suratPengajuan);

        session()->flash('status', 'Draft surat pengajuan berhasil dihapus.');

        return $this->redirect(route('pengajuan.index'), navigate: true);
    }

    public function render(ComplianceService $complianceService): View
    {
        $metrics = $complianceService->calculateComplianceMetrics($this->suratPengajuan);

        $user = auth()->user();

        return view('livewire.hasil-akreditasi.index', [
            'metrics' => $metrics,
            'isAdmin' => $user?->isAdmin() ?? false,
            'canDecide' => $user?->can('decide', $this->suratPengajuan) ?? false,
        ])->layout('layouts.app');
    }
}

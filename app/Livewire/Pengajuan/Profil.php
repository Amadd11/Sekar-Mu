<?php

namespace App\Livewire\Pengajuan;

use App\Models\AnggotaKepk;
use App\Models\SuratPengajuan;
use App\Services\PengajuanService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Profil extends Component
{
    public SuratPengajuan $suratPengajuan;

    public string $deskripsi = '';
    public string $visi = '';
    public string $misi = '';

    public string $nama_anggota = '';
    public string $jabatan_anggota = '';
    public string $email_anggota = '';
    public string $telepon_anggota = '';

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('update', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load(['profilKepk', 'anggotaKepk']);

        if ($profil = $suratPengajuan->profilKepk) {
            $this->deskripsi = $profil->deskripsi ?? '';
            $this->visi = $profil->visi ?? '';
            $this->misi = $profil->misi ?? '';
        }
    }

    public function saveProfil(PengajuanService $service): void
    {
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

        session()->flash('status', 'Profil, visi, dan misi KEPK berhasil disimpan.');
    }

    public function tambahAnggota(PengajuanService $service): void
    {
        $this->validate([
            'nama_anggota' => ['required', 'string', 'max:255'],
            'jabatan_anggota' => ['nullable', 'string', 'max:100'],
            'email_anggota' => ['nullable', 'email', 'max:100'],
            'telepon_anggota' => ['nullable', 'string', 'max:50'],
        ]);

        $service->addMember($this->suratPengajuan, [
            'nama' => $this->nama_anggota,
            'jabatan' => $this->jabatan_anggota,
            'email' => $this->email_anggota,
            'telepon' => $this->telepon_anggota,
        ]);

        $this->reset(['nama_anggota', 'jabatan_anggota', 'email_anggota', 'telepon_anggota']);
        $this->suratPengajuan->refresh();

        session()->flash('anggota_status', 'Anggota KEPK berhasil ditambahkan.');
    }

    public function hapusAnggota(int $anggotaId, PengajuanService $service): void
    {
        $anggota = AnggotaKepk::findOrFail($anggotaId);
        $service->removeMember($anggota);
        $this->suratPengajuan->refresh();

        session()->flash('anggota_status', 'Anggota KEPK berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.pengajuan.profil')->layout('layouts.app');
    }
}

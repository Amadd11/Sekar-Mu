<?php

namespace App\Services;

use App\Models\AnggotaKepk;
use App\Models\FormulirAplikasi;
use App\Models\ProfilKepk;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PengajuanService
{
    /**
     * Create a new draft application.
     *
     * @param  array<string, mixed>  $data
     */
    public function createDraft(User $user, array $data): SuratPengajuan
    {
        return DB::transaction(function () use ($user, $data) {
            $surat = SuratPengajuan::create([
                'user_id' => $user->id,
                'kepk_id' => $data['kepk_id'],
                'status' => 'draft',
            ]);

            FormulirAplikasi::create([
                'surat_pengajuan_id' => $surat->id,
                'nama_institusi' => $data['nama_institusi'],
                'singkatan' => $data['singkatan'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'kota' => $data['kota'] ?? null,
                'telepon' => $data['telepon'] ?? null,
                'email' => $data['email'] ?? null,
            ]);

            ProfilKepk::create([
                'surat_pengajuan_id' => $surat->id,
                'deskripsi' => $data['deskripsi'] ?? null,
                'visi' => $data['visi'] ?? null,
                'misi' => $data['misi'] ?? null,
            ]);

            return $surat->load(['formulirAplikasi', 'profilKepk']);
        });
    }

    /**
     * Update application form information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateFormulirAplikasi(SuratPengajuan $surat, array $data): FormulirAplikasi
    {
        return DB::transaction(function () use ($surat, $data) {
            return FormulirAplikasi::updateOrCreate(
                ['surat_pengajuan_id' => $surat->id],
                [
                    'nama_institusi' => $data['nama_institusi'],
                    'singkatan' => $data['singkatan'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                    'kota' => $data['kota'] ?? null,
                    'telepon' => $data['telepon'] ?? null,
                    'email' => $data['email'] ?? null,
                ]
            );
        });
    }

    /**
     * Update KEPK profile, vision, and mission.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfilKepk(SuratPengajuan $surat, array $data): ProfilKepk
    {
        return DB::transaction(function () use ($surat, $data) {
            return ProfilKepk::updateOrCreate(
                ['surat_pengajuan_id' => $surat->id],
                [
                    'deskripsi' => $data['deskripsi'] ?? null,
                    'visi' => $data['visi'] ?? null,
                    'misi' => $data['misi'] ?? null,
                ]
            );
        });
    }

    /**
     * Add an organization member to the application.
     *
     * @param  array<string, mixed>  $data
     */
    public function addMember(SuratPengajuan $surat, array $data): AnggotaKepk
    {
        return DB::transaction(function () use ($surat, $data) {
            return AnggotaKepk::create([
                'surat_pengajuan_id' => $surat->id,
                'nama' => $data['nama'],
                'jabatan' => $data['jabatan'] ?? null,
                'email' => $data['email'] ?? null,
                'telepon' => $data['telepon'] ?? null,
            ]);
        });
    }

    /**
     * Remove an organization member.
     */
    public function removeMember(AnggotaKepk $anggota): bool
    {
        return DB::transaction(function () use ($anggota) {
            return (bool) $anggota->delete();
        });
    }

    /**
     * Submit or resubmit application.
     */
    public function submit(SuratPengajuan $surat): SuratPengajuan
    {
        return DB::transaction(function () use ($surat) {
            $statusBaru = $surat->status === 'revision_required' ? 'resubmitted' : 'submitted';

            $surat->update([
                'status' => $statusBaru,
                'diajukan_pada' => now(),
            ]);

            return $surat->fresh();
        });
    }

    /**
     * Delete application draft.
     */
    public function delete(SuratPengajuan $surat): bool
    {
        return DB::transaction(function () use ($surat) {
            return (bool) $surat->delete();
        });
    }
}

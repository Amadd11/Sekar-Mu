<?php

namespace App\Services;

use App\Models\Dokumen;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenService
{
    /**
     * Upload and store an attachment document file.
     */
    public function upload(SuratPengajuan $surat, User $user, UploadedFile $file): Dokumen
    {
        return DB::transaction(function () use ($surat, $user, $file) {
            $namaAsli = $file->getClientOriginalName();
            $ekstensi = $file->getClientOriginalExtension();
            $namaTersimpan = Str::uuid() . ($ekstensi ? '.' . $ekstensi : '');
            $path = $file->storeAs('dokumen/' . $surat->id, $namaTersimpan, 'public');

            return Dokumen::create([
                'surat_pengajuan_id' => $surat->id,
                'diunggah_oleh' => $user->id,
                'nama_asli' => $namaAsli,
                'nama_tersimpan' => $namaTersimpan,
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'ukuran' => $file->getSize() ?: 0,
            ]);
        });
    }

    /**
     * Delete an attachment document record and its physical storage file.
     */
    public function delete(Dokumen $dokumen): bool
    {
        return DB::transaction(function () use ($dokumen) {
            if ($dokumen->path && Storage::disk('public')->exists($dokumen->path)) {
                Storage::disk('public')->delete($dokumen->path);
            }

            return (bool) $dokumen->delete();
        });
    }
}

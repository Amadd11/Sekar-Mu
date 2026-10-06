<?php

namespace App\Services;

use App\Models\ListProtokol;
use App\Models\SuratPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListProtokolService
{
    /**
     * Create a research protocol record.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(SuratPengajuan $surat, array $data): ListProtokol
    {
        return DB::transaction(function () use ($surat, $data) {
            return ListProtokol::create([
                'surat_pengajuan_id' => $surat->id,
                'nomor_protokol' => $data['nomor_protokol'],
                'judul' => $data['judul'],
                'peneliti_utama' => $data['peneliti_utama'],
                'institusi_asal' => $data['institusi_asal'] ?? null,
                'review_type' => $data['review_type'] ?? 'expedited',
                'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? now()->toDateString(),
                'status' => $data['status'] ?? 'approved',
                'dokumen_path' => $data['dokumen_path'] ?? null,
                'dokumen_nama' => $data['dokumen_nama'] ?? null,
                'dokumen_ukuran' => $data['dokumen_ukuran'] ?? null,
            ]);
        });
    }

    /**
     * Update a research protocol record.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(ListProtokol $protokol, array $data): ListProtokol
    {
        return DB::transaction(function () use ($protokol, $data) {
            $updateData = [
                'nomor_protokol' => $data['nomor_protokol'],
                'judul' => $data['judul'],
                'peneliti_utama' => $data['peneliti_utama'],
                'institusi_asal' => $data['institusi_asal'] ?? $protokol->institusi_asal,
                'review_type' => $data['review_type'] ?? $protokol->review_type,
                'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? $protokol->tanggal_pengajuan,
                'status' => $data['status'] ?? $protokol->status,
            ];

            if (array_key_exists('dokumen_path', $data)) {
                if ($protokol->dokumen_path && $protokol->dokumen_path !== $data['dokumen_path']) {
                    if (Storage::disk('public')->exists($protokol->dokumen_path)) {
                        Storage::disk('public')->delete($protokol->dokumen_path);
                    }
                }
                $updateData['dokumen_path'] = $data['dokumen_path'];
                $updateData['dokumen_nama'] = $data['dokumen_nama'] ?? null;
                $updateData['dokumen_ukuran'] = $data['dokumen_ukuran'] ?? null;
            }

            $protokol->update($updateData);

            return $protokol;
        });
    }

    /**
     * Delete a research protocol record.
     */
    public function delete(ListProtokol $protokol): bool
    {
        return DB::transaction(function () use ($protokol) {
            if ($protokol->dokumen_path && Storage::disk('public')->exists($protokol->dokumen_path)) {
                Storage::disk('public')->delete($protokol->dokumen_path);
            }

            return (bool) $protokol->delete();
        });
    }
}

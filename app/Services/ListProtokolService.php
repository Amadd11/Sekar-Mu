<?php

namespace App\Services;

use App\Models\ListProtokol;
use App\Models\SuratPengajuan;
use Illuminate\Support\Facades\DB;

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
                'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? now()->toDateString(),
                'status' => $data['status'] ?? 'draft',
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
            $protokol->update([
                'nomor_protokol' => $data['nomor_protokol'],
                'judul' => $data['judul'],
                'peneliti_utama' => $data['peneliti_utama'],
                'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? $protokol->tanggal_pengajuan,
                'status' => $data['status'] ?? $protokol->status,
            ]);

            return $protokol;
        });
    }

    /**
     * Delete a research protocol record.
     */
    public function delete(ListProtokol $protokol): bool
    {
        return DB::transaction(function () use ($protokol) {
            return (bool) $protokol->delete();
        });
    }
}

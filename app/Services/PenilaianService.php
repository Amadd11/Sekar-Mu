<?php

namespace App\Services;

use App\Models\CatatanPenilaian;
use App\Models\PenilaiPengajuan;
use App\Models\PenilaianEtik;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PenilaianService
{
    /**
     * Assign reviewers/assessors to an application.
     *
     * @param  list<int>  $penilaiIds
     */
    public function assignReviewers(SuratPengajuan $surat, array $penilaiIds, User $penugas): void
    {
        DB::transaction(function () use ($surat, $penilaiIds, $penugas) {
            foreach ($penilaiIds as $penilaiId) {
                PenilaiPengajuan::firstOrCreate(
                    [
                        'surat_pengajuan_id' => $surat->id,
                        'user_id' => $penilaiId,
                    ],
                    [
                        'ditugaskan_oleh' => $penugas->id,
                        'tanggal_penugasan' => now(),
                    ]
                );
            }

            if (in_array($surat->status, ['submitted', 'resubmitted'], true)) {
                $surat->update(['status' => 'under_review']);
            }
        });
    }

    /**
     * Remove a reviewer assignment from an application.
     */
    public function removeReviewer(SuratPengajuan $surat, int $penilaiId): void
    {
        DB::transaction(function () use ($surat, $penilaiId) {
            PenilaiPengajuan::where('surat_pengajuan_id', $surat->id)
                ->where('user_id', $penilaiId)
                ->delete();
        });
    }

    /**
     * Submit or update a review recommendation.
     *
     * @param  array<string, mixed>  $data
     */
    public function submitReview(SuratPengajuan $surat, User $penilai, array $data): PenilaianEtik
    {
        return DB::transaction(function () use ($surat, $penilai, $data) {
            $penilaian = PenilaianEtik::updateOrCreate(
                [
                    'surat_pengajuan_id' => $surat->id,
                    'penilai_id' => $penilai->id,
                ],
                [
                    'rekomendasi' => $data['rekomendasi'],
                    'catatan' => $data['catatan'] ?? null,
                    'tanggal_keputusan' => now()->toDateString(),
                ]
            );

            if ($data['rekomendasi'] === 'revision_required') {
                $surat->update(['status' => 'revision_required']);
            }

            return $penilaian;
        });
    }

    /**
     * Add a review comment note.
     */
    public function addComment(PenilaianEtik $penilaian, User $user, string $catatan): CatatanPenilaian
    {
        return DB::transaction(function () use ($penilaian, $user, $catatan) {
            return CatatanPenilaian::create([
                'penilaian_etik_id' => $penilaian->id,
                'user_id' => $user->id,
                'catatan' => $catatan,
                'selesai' => false,
            ]);
        });
    }

    /**
     * Toggle resolved state on a review comment.
     */
    public function toggleResolveComment(CatatanPenilaian $catatan): void
    {
        DB::transaction(function () use ($catatan) {
            $catatan->update([
                'selesai' => ! $catatan->selesai,
            ]);
        });
    }

    /**
     * Finalize the committee's decision on the application.
     */
    public function finalizeDecision(SuratPengajuan $surat, string $keputusan): SuratPengajuan
    {
        return DB::transaction(function () use ($surat, $keputusan) {
            $surat->update([
                'status' => $keputusan,
            ]);

            return $surat->fresh();
        });
    }
}

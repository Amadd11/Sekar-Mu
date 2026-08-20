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
     * Save an independent item assessment by a reviewer.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveItemAssessment(SuratPengajuan $surat, User $penilai, int $butirId, array $data): \App\Models\PenilaianButirAsesor
    {
        return DB::transaction(function () use ($surat, $penilai, $butirId, $data) {
            return \App\Models\PenilaianButirAsesor::updateOrCreate(
                [
                    'surat_pengajuan_id' => $surat->id,
                    'penilai_id' => $penilai->id,
                    'butir_evaluasi_id' => $butirId,
                ],
                [
                    'skor' => $data['skor'] ?? null,
                    'evidence_strength' => $data['evidence_strength'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'temuan' => $data['temuan'] ?? null,
                    'rekomendasi' => $data['rekomendasi'] ?? null,
                ]
            );
        });
    }

    /**
     * Generate Comparison Matrix: Self-Assessment vs Assessor Score vs Gap per item.
     *
     * @return array<string, mixed>
     */
    public function getComparisonMatrix(SuratPengajuan $surat, ?int $penilaiId = null): array
    {
        $allSections = \App\Models\BagianEvaluasi::with(['butir.kelompok'])->orderBy('urutan')->get();
        $selfAnswers = $surat->jawabanEvaluasi()->get()->keyBy('butir_evaluasi_id');

        $assessorQuery = \App\Models\PenilaianButirAsesor::where('surat_pengajuan_id', $surat->id);
        if ($penilaiId) {
            $assessorQuery->where('penilai_id', $penilaiId);
        }
        $assessorAnswers = $assessorQuery->get()->keyBy('butir_evaluasi_id');

        $matrix = [];
        $totalItems = 0;
        $totalMatches = 0;
        $totalGaps = 0;

        foreach ($allSections as $section) {
            $sectionRows = [];

            foreach ($section->butir as $item) {
                $totalItems++;
                $selfAns = $selfAnswers->get($item->id);
                $assessorAns = $assessorAnswers->get($item->id);

                $selfScore = $selfAns?->skor;
                $assessorScore = $assessorAns?->skor;

                $hasGap = false;
                $gapDescription = '0 (Sesuai)';

                if ($selfScore && $assessorScore) {
                    if ($selfScore !== $assessorScore) {
                        $hasGap = true;
                        $totalGaps++;
                        $gapDescription = "Gap ({$selfScore} vs {$assessorScore})";
                    } else {
                        $totalMatches++;
                    }
                } elseif ($selfScore || $assessorScore) {
                    $hasGap = true;
                    $totalGaps++;
                    $gapDescription = 'Gap (Belum Lengkap)';
                }

                $sectionRows[] = [
                    'item_id' => $item->id,
                    'kode_butir' => "{$section->kode}.{$item->urutan}",
                    'pertanyaan' => $item->pertanyaan,
                    'is_critical' => $item->is_critical,
                    'self_score' => $selfScore ?? '-',
                    'self_catatan' => $selfAns?->catatan,
                    'self_bukti' => $selfAns?->bukti,
                    'assessor_score' => $assessorScore ?? '-',
                    'assessor_catatan' => $assessorAns?->catatan,
                    'assessor_temuan' => $assessorAns?->temuan,
                    'has_gap' => $hasGap,
                    'gap_label' => $gapDescription,
                ];
            }

            $matrix[$section->kode] = [
                'section_name' => $section->nama,
                'items' => $sectionRows,
            ];
        }

        return [
            'total_items' => $totalItems,
            'total_matches' => $totalMatches,
            'total_gaps' => $totalGaps,
            'sections' => $matrix,
        ];
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

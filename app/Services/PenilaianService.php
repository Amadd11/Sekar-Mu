<?php

namespace App\Services;

use App\Models\BagianEvaluasi;
use App\Models\CatatanPenilaian;
use App\Models\PenilaiPengajuan;
use App\Models\PenilaianButirAsesor;
use App\Models\PenilaianEtik;
use App\Models\SuratPengajuan;
use App\Models\User;

class PenilaianService
{
    /**
     * Assign reviewers/assessors to an application.
     *
     * @param  list<int>  $penilaiIds
     */
    public function assignReviewers(SuratPengajuan $surat, array $penilaiIds, User $penugas): void
    {
        $pivotData = [];
        foreach ($penilaiIds as $penilaiId) {
            $pivotData[$penilaiId] = [
                'ditugaskan_oleh' => $penugas->id,
                'tanggal_penugasan' => now(),
            ];
        }

        $surat->penilai()->sync($pivotData);
    }

    /**
     * Remove a reviewer assignment from an application.
     */
    public function removeReviewer(SuratPengajuan $surat, int $penilaiId): void
    {
        PenilaiPengajuan::where('surat_pengajuan_id', $surat->id)
            ->where('user_id', $penilaiId)
            ->delete();
    }

    /**
     * Submit or update a review recommendation.
     *
     * @param  array<string, mixed>  $data
     */
    public function submitReview(SuratPengajuan $surat, User $penilai, array $data): PenilaianEtik
    {
        return PenilaianEtik::updateOrCreate(
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
    }

    /**
     * Add a review comment note.
     */
    public function addComment(PenilaianEtik $penilaian, User $user, string $catatan): CatatanPenilaian
    {
        return CatatanPenilaian::create([
            'penilaian_etik_id' => $penilaian->id,
            'user_id' => $user->id,
            'catatan' => $catatan,
            'selesai' => false,
        ]);
    }

    /**
     * Toggle resolved state on a review comment.
     */
    public function toggleResolveComment(CatatanPenilaian $catatan): void
    {
        $catatan->update([
            'selesai' => ! $catatan->selesai,
        ]);
    }

    /**
     * Save an independent item assessment by a reviewer.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveItemAssessment(SuratPengajuan $surat, User $penilai, int $butirId, array $data): PenilaianButirAsesor
    {
        return PenilaianButirAsesor::updateOrCreate(
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
    }

    /**
     * Generate Comparison Matrix: Self-Assessment vs Assessor Score vs Gap per item.
     *
     * @return array<string, mixed>
     */
    public function getComparisonMatrix(SuratPengajuan $surat, ?int $penilaiId = null): array
    {
        $allSections = BagianEvaluasi::with(['butir.kelompok'])->orderBy('urutan')->get();
        $selfAnswers = $surat->jawabanEvaluasi()->get()->keyBy('butir_evaluasi_id');

        $assessorQuery = PenilaianButirAsesor::where('surat_pengajuan_id', $surat->id);
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

                $gap = $this->compareScores($selfScore, $assessorScore);

                if ($gap['has_gap']) {
                    $totalGaps++;
                } elseif ($gap['type'] === 'match') {
                    $totalMatches++;
                }

                $sectionRows[] = [
                    'item_id' => $item->id,
                    'kode_butir' => $item->kode ?? "{$section->kode}.{$item->id}",
                    'pertanyaan' => $item->pertanyaan,
                    'self_score' => $selfScore ?? '-',
                    'self_catatan' => $selfAns?->catatan,
                    'self_bukti' => $selfAns?->bukti,
                    'assessor_score' => $assessorScore ?? '-',
                    'assessor_catatan' => $assessorAns?->catatan,
                    'assessor_temuan' => $assessorAns?->temuan,
                    'has_gap' => $gap['has_gap'],
                    'gap_type' => $gap['type'],
                    'gap_label' => $this->gapLabel($gap['type'], $selfScore, $assessorScore),
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

    private function compareScores(mixed $selfScore, mixed $assessorScore): array
    {
        if ($selfScore && $assessorScore) {
            return $selfScore === $assessorScore
                ? ['has_gap' => false, 'type' => 'match']
                : ['has_gap' => true, 'type' => 'mismatch'];
        }

        if ($selfScore || $assessorScore) {
            return ['has_gap' => true, 'type' => 'incomplete'];
        }

        return ['has_gap' => false, 'type' => 'empty'];
    }

    private function gapLabel(string $type, mixed $selfScore, mixed $assessorScore): string
    {
        return match ($type) {
            'mismatch' => "Gap ({$selfScore} vs {$assessorScore})",
            'incomplete' => 'Gap (Belum Lengkap)',
            default => '0 (Sesuai)',
        };
    }

    /**
     * Finalize the committee's decision on the application.
     */
    public function finalizeDecision(SuratPengajuan $surat, string $keputusan): SuratPengajuan
    {
        $surat->update([
            'status' => $keputusan,
        ]);

        return $surat->fresh();
    }
}
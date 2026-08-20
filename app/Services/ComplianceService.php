<?php

namespace App\Services;

use App\Models\BagianEvaluasi;
use App\Models\ButirEvaluasi;
use App\Models\SuratPengajuan;

class ComplianceService
{
    /**
     * Calculate comprehensive section scores, answered counts, and compliance percentages.
     *
     * @return array<string, mixed>
     */
    public function calculateComplianceMetrics(SuratPengajuan $surat): array
    {
        $allSections = BagianEvaluasi::with(['butir.kelompok'])->orderBy('urutan')->get();
        $selfAnswers = $surat->jawabanEvaluasi()->with('butir')->get()->keyBy('butir_evaluasi_id');
        $assessorScores = \App\Models\PenilaianButirAsesor::where('surat_pengajuan_id', $surat->id)->get()->keyBy('butir_evaluasi_id');

        $hasAssessorEvaluations = $assessorScores->isNotEmpty();

        $totalItemsCount = 0;
        $totalAnsweredCount = 0;
        $totalScorePoints = 0.0;
        $countA = 0;
        $countB = 0;
        $countC = 0;
        $countD = 0;

        $sectionsData = [];

        foreach ($allSections as $section) {
            $sectionItems = $section->butir;
            $sectionTotalItems = $sectionItems->count();
            $totalItemsCount += $sectionTotalItems;

            $secAnswered = 0;
            $secScorePoints = 0.0;
            $secA = 0;
            $secB = 0;
            $secC = 0;
            $secD = 0;

            foreach ($sectionItems as $item) {
                $ans = $selfAnswers->get($item->id);
                $ass = $assessorScores->get($item->id);

                // Prioritize Assessor's verified score if present, else fallback to self-assessment
                $effectiveScore = $ass?->skor ?? $ans?->skor;

                if (! empty($effectiveScore)) {
                    $secAnswered++;
                    $totalAnsweredCount++;

                    match ($effectiveScore) {
                        'A' => ($countA++ && $secA++ && $secScorePoints += 1.0 && $totalScorePoints += 1.0),
                        'B' => ($countB++ && $secB++ && $secScorePoints += 0.5 && $totalScorePoints += 0.5),
                        'C' => ($countC++ && $secC++),
                        'D' => ($countD++ && $secD++),
                        default => null,
                    };
                }
            }

            // PRD Sec 1.2: D (Tidak Dapat Dinilai) dikeluarkan dari pembagi/denominator
            $secDenominator = max(1, $sectionTotalItems - $secD);
            $secPercentage = (int) round(($secScorePoints / $secDenominator) * 100);

            $secCompletion = $sectionTotalItems > 0
                ? (int) round(($secAnswered / $sectionTotalItems) * 100)
                : 0;

            $sectionsData[$section->kode] = [
                'id' => $section->id,
                'kode' => $section->kode,
                'nama' => $section->nama,
                'total_items' => $sectionTotalItems,
                'answered_items' => $secAnswered,
                'completion_percentage' => $secCompletion,
                'compliance_percentage' => $secPercentage,
                'score_points' => $secScorePoints,
                'counts' => [
                    'A' => $secA,
                    'B' => $secB,
                    'C' => $secC,
                    'D' => $secD,
                ],
            ];
        }

        // PRD Sec 1.2: Total denominator excludes D items
        $totalDenominator = max(1, $totalItemsCount - $countD);
        $overallCompliance = (int) round(($totalScorePoints / $totalDenominator) * 100);

        $overallCompletion = $totalItemsCount > 0
            ? (int) round(($totalAnsweredCount / $totalItemsCount) * 100)
            : 0;

        $prediction = $this->classifyAccreditation($overallCompliance, $countC, $overallCompletion);
        $criticalFindings = $this->getCriticalFindings($surat);

        $countsData = [
            'total' => $totalAnsweredCount,
            'A' => $countA,
            'B' => $countB,
            'C' => $countC,
            'D' => $countD,
        ];

        return [
            'total_items' => $totalItemsCount,
            'total_answered' => $totalAnsweredCount,
            'total_score_points' => $totalScorePoints,
            'overall_compliance' => $overallCompliance,
            'compliance_percentage' => $overallCompliance,
            'overall_completion' => $overallCompletion,
            'completion_percentage' => $overallCompletion,
            'counts' => $countsData,
            'score_counts' => $countsData,
            'critical_non_compliance_count' => count($criticalFindings),
            'critical_findings' => $criticalFindings,
            'sections' => $sectionsData,
            'prediction' => $prediction,
        ];
    }

    /**
     * Classify accreditation type based on PRD v2.0 rules:
     * - Tipe A: score >= 80% AND count(C) == 0
     * - Tipe B: score >= 65% AND count(C) <= 5
     * - Tipe C: score >= 50%
     * - Belum Memenuhi Syarat: score < 50%
     *
     * @return array<string, mixed>
     */
    public function classifyAccreditation(int $scorePercentage, int $countC, int $completionPercentage = 100): array
    {
        if ($scorePercentage >= 80 && $countC === 0) {
            $badge = 'bg-emerald-100 text-emerald-800 border-emerald-300';

            return [
                'type' => 'Tipe A',
                'grade' => 'A',
                'title' => 'Prediksi: Tipe A (Terakreditasi Penuh)',
                'description' => 'Memenuhi standar mutu paripurna (Skor ≥ 80% tanpa nilai C).',
                'badge' => $badge,
                'badge_class' => $badge,
                'bg_gradient' => 'from-emerald-600 to-teal-700',
                'is_passed' => true,
            ];
        }

        if ($scorePercentage >= 65 && $countC <= 5) {
            $badge = 'bg-blue-100 text-blue-800 border-blue-300';

            return [
                'type' => 'Tipe B',
                'grade' => 'B',
                'title' => 'Prediksi: Tipe B (Terakreditasi Bersyarat)',
                'description' => 'Memenuhi standar utama (Skor ≥ 65% dengan nilai C ≤ 5 butir).',
                'badge' => $badge,
                'badge_class' => $badge,
                'bg_gradient' => 'from-blue-600 to-indigo-700',
                'is_passed' => true,
            ];
        }

        if ($scorePercentage >= 50) {
            $badge = 'bg-amber-100 text-amber-800 border-amber-300';

            return [
                'type' => 'Tipe C',
                'grade' => 'C',
                'title' => 'Prediksi: Tipe C (Terakreditasi Minimal)',
                'description' => 'Memenuhi standar minimum (Skor ≥ 50%). Memerlukan tindakan perbaikan segera.',
                'badge' => $badge,
                'badge_class' => $badge,
                'bg_gradient' => 'from-amber-600 to-orange-700',
                'is_passed' => true,
            ];
        }

        $badge = 'bg-rose-100 text-rose-800 border-rose-300';

        return [
            'type' => 'Belum Memenuhi Syarat',
            'grade' => 'N/A',
            'title' => 'Prediksi: Belum Memenuhi Syarat',
            'description' => 'Skor kepatuhan di bawah 50%. Belum memenuhi ambang batas akreditasi KEPK.',
            'badge' => $badge,
            'badge_class' => $badge,
            'bg_gradient' => 'from-rose-600 to-red-700',
            'is_passed' => false,
        ];
    }

    /**
     * Identify critical findings: items where critical = true AND score = C.
     *
     * @return list<array<string, mixed>>
     */
    public function getCriticalFindings(SuratPengajuan $surat): array
    {
        $selfAnswers = $surat->jawabanEvaluasi()->with(['butir.kelompok.bagian'])->get()->keyBy('butir_evaluasi_id');
        $assessorScores = \App\Models\PenilaianButirAsesor::where('surat_pengajuan_id', $surat->id)->get()->keyBy('butir_evaluasi_id');

        $allCriticalButir = ButirEvaluasi::with('kelompok.bagian')->where('is_critical', true)->get();
        $findings = [];

        foreach ($allCriticalButir as $butir) {
            $ans = $selfAnswers->get($butir->id);
            $ass = $assessorScores->get($butir->id);

            $effectiveScore = $ass?->skor ?? $ans?->skor;

            if ($effectiveScore === 'C') {
                $bagian = $butir->kelompok?->bagian;
                $findings[] = [
                    'butir_id' => $butir->id,
                    'kode_bagian' => $bagian?->kode ?? '-',
                    'nama_bagian' => $bagian?->nama ?? '-',
                    'urutan' => $butir->urutan,
                    'pertanyaan' => $butir->pertanyaan,
                    'standar' => $butir->standar,
                    'catatan' => $ass?->catatan ?? $ans?->catatan,
                    'temuan' => $ass?->temuan,
                    'risk_level' => 'HIGH',
                    'action_required' => 'Wajib membuat Rencana Tindakan Korektif (Corrective Action Plan) sebelum finalisasi.',
                ];
            }
        }

        return $findings;
    }

    /**
     * Generate Gap Analysis & Top 10 improvement opportunities.
     *
     * @return array<string, mixed>
     */
    public function calculateGapAnalysis(SuratPengajuan $surat, int $targetScore = 80): array
    {
        $metrics = $this->calculateComplianceMetrics($surat);
        $currentScore = $metrics['overall_compliance'];
        $scoreGap = max(0, $targetScore - $currentScore);

        $selfAnswers = $surat->jawabanEvaluasi()
            ->with(['butir.kelompok.bagian'])
            ->get()
            ->keyBy('butir_evaluasi_id');

        $assessorScores = \App\Models\PenilaianButirAsesor::where('surat_pengajuan_id', $surat->id)
            ->get()
            ->keyBy('butir_evaluasi_id');

        $allButir = ButirEvaluasi::with('kelompok.bagian')->orderBy('urutan')->get();

        $opportunities = [];

        foreach ($allButir as $butir) {
            $ans = $selfAnswers->get($butir->id);
            $ass = $assessorScores->get($butir->id);

            $skor = $ass?->skor ?? $ans?->skor;

            if ($skor === 'C' || $skor === 'B' || empty($skor)) {
                $priority = $butir->is_critical
                    ? 'HIGH'
                    : ($skor === 'C' ? 'MEDIUM' : 'LOW');

                $potentialGain = match ($skor) {
                    'C', null => 1.0, // moving from 0 to 1.0
                    'B' => 0.5,      // moving from 0.5 to 1.0
                    default => 0.0,
                };

                $opportunities[] = [
                    'butir_id' => $butir->id,
                    'kode_bagian' => $butir->kelompok?->bagian?->kode ?? 'A',
                    'urutan' => $butir->urutan,
                    'pertanyaan' => $butir->pertanyaan,
                    'is_critical' => $butir->is_critical,
                    'current_score' => $skor ?? 'Belum Diisi',
                    'priority' => $priority,
                    'potential_gain' => $potentialGain,
                ];
            }
        }

        // Sort opportunities: HIGH priority first, then potential gain descending, then urutan
        usort($opportunities, function ($a, $b) {
            $prioOrder = ['HIGH' => 3, 'MEDIUM' => 2, 'LOW' => 1];
            $prioA = $prioOrder[$a['priority']] ?? 0;
            $prioB = $prioOrder[$b['priority']] ?? 0;

            if ($prioA !== $prioB) {
                return $prioB <=> $prioA;
            }

            return $a['urutan'] <=> $b['urutan'];
        });

        $top10 = array_slice($opportunities, 0, 10);
        $criticalFindings = $this->getCriticalFindings($surat);

        return [
            'current_score' => $currentScore,
            'target_score' => $targetScore,
            'score_gap' => $scoreGap,
            'critical_findings_count' => count($criticalFindings),
            'critical_findings' => $criticalFindings,
            'total_gaps_count' => count($opportunities),
            'top_improvements' => $top10,
        ];
    }
}

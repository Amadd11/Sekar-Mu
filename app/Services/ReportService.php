<?php

namespace App\Services;

use App\Models\BagianEvaluasi;
use App\Models\SuratPengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportService
{
    public function __construct(
        protected ComplianceService $complianceService,
        protected PenilaianService $penilaianService,
    ) {}

    /**
     * Generate PDF for 164-item Self Evaluation (Evaluasi Mandiri KEPK).
     */
    public function generateEvaluationReport(SuratPengajuan $surat): \Barryvdh\DomPDF\PDF
    {
        $surat->load([
            'kepk.institusi',
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'jawabanEvaluasi.butir.kelompok.bagian',
            'penilaianButirAsesor',
            'user',
        ]);

        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $metrics = $this->complianceService->calculateComplianceMetrics($surat);
        $selfAnswers = $surat->jawabanEvaluasi->keyBy('butir_evaluasi_id');
        $assessorScores = $surat->penilaianButirAsesor->keyBy('butir_evaluasi_id');

        return Pdf::loadView('pdf.evaluasi-diri', [
            'surat' => $surat,
            'bagianList' => $bagianList,
            'metrics' => $metrics,
            'selfAnswers' => $selfAnswers,
            'assessorScores' => $assessorScores,
            'printedAt' => now()->translatedFormat('d F Y, H:i'),
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Generate Official Accreditation Result & Executive Summary Report.
     */
    public function generateAccreditationReport(SuratPengajuan $surat): \Barryvdh\DomPDF\PDF
    {
        $surat->load([
            'kepk.institusi',
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'penilai',
            'penilaianEtik.penilai',
            'penilaianButirAsesor.butir.kelompok.bagian',
            'correctiveActions.butir',
            'user',
        ]);

        $metrics = $this->complianceService->calculateComplianceMetrics($surat);
        $gapAnalysis = $this->complianceService->calculateGapAnalysis($surat);
        $criticalFindings = $this->complianceService->getCriticalFindings($surat);
        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();

        return Pdf::loadView('pdf.hasil-akreditasi', [
            'surat' => $surat,
            'metrics' => $metrics,
            'gapAnalysis' => $gapAnalysis,
            'criticalFindings' => $criticalFindings,
            'bagianList' => $bagianList,
            'printedAt' => now()->translatedFormat('d F Y, H:i'),
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Generate Comparison Matrix (Self-Assessment vs Assessor Score vs Gap).
     */
    public function generateComparisonMatrixReport(SuratPengajuan $surat): \Barryvdh\DomPDF\PDF
    {
        $surat->load([
            'kepk.institusi',
            'formulirAplikasi',
            'penilai',
            'user',
        ]);

        $matrix = $this->penilaianService->getComparisonMatrix($surat);
        $metrics = $this->complianceService->calculateComplianceMetrics($surat);

        return Pdf::loadView('pdf.matriks-gap', [
            'surat' => $surat,
            'matrix' => $matrix,
            'metrics' => $metrics,
            'printedAt' => now()->translatedFormat('d F Y, H:i'),
        ])->setPaper('a4', 'landscape');
    }
}

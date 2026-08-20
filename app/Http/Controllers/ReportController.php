<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Services\ReportService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
    ) {}

    /**
     * Download or stream Laporan Hasil Akreditasi & Ringkasan Eksekutif (PDF).
     */
    public function hasilAkreditasi(SuratPengajuan $suratPengajuan): Response
    {
        Gate::authorize('view', $suratPengajuan);

        $pdf = $this->reportService->generateAccreditationReport($suratPengajuan);
        $fileName = 'Laporan_Akreditasi_KEPK_APP_' . str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Download or stream Borang Evaluasi Diri 164 Butir (PDF).
     */
    public function evaluasiDiri(SuratPengajuan $suratPengajuan): Response
    {
        Gate::authorize('view', $suratPengajuan);

        $pdf = $this->reportService->generateEvaluationReport($suratPengajuan);
        $fileName = 'Evaluasi_Diri_164_Butir_APP_' . str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Download or stream Matriks Komparasi Gap Evaluasi vs Asesor (PDF).
     */
    public function matriksGap(SuratPengajuan $suratPengajuan): Response
    {
        Gate::authorize('view', $suratPengajuan);

        $pdf = $this->reportService->generateComparisonMatrixReport($suratPengajuan);
        $fileName = 'Matriks_Komparasi_Gap_APP_' . str_pad($suratPengajuan->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->stream($fileName);
    }
}

<?php

namespace App\Services;

use App\Models\BagianEvaluasi;
use App\Models\JawabanEvaluasi;
use App\Models\SuratPengajuan;
use Illuminate\Support\Facades\DB;

class EvaluasiDiriService
{
    /**
     * Save or update an assessment item answer.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveAnswer(SuratPengajuan $surat, int $butirId, array $data): JawabanEvaluasi
    {
        return DB::transaction(function () use ($surat, $butirId, $data) {
            return JawabanEvaluasi::updateOrCreate(
                [
                    'surat_pengajuan_id' => $surat->id,
                    'butir_evaluasi_id' => $butirId,
                ],
                [
                    'skor' => $data['skor'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'bukti' => $data['bukti'] ?? null,
                ]
            );
        });
    }

    /**
     * Calculate completion progress for each section (A to E).
     *
     * @return array<string, array<string, mixed>>
     */
    public function calculateProgress(SuratPengajuan $surat): array
    {
        $semuaBagian = BagianEvaluasi::with('butir')->orderBy('urutan')->get();
        $idTerjawab = $surat->jawabanEvaluasi()
            ->whereNotNull('skor')
            ->pluck('butir_evaluasi_id')
            ->toArray();

        $progress = [];

        foreach ($semuaBagian as $bagian) {
            $totalButir = $bagian->butir->count();
            $idButirBagian = $bagian->butir->pluck('id')->toArray();
            $terjawabDiBagian = count(array_intersect($idButirBagian, $idTerjawab));
            $persentase = $totalButir > 0 ? (int) round(($terjawabDiBagian / $totalButir) * 100) : 0;

            $progress[$bagian->kode] = [
                'nama' => $bagian->nama,
                'total' => $totalButir,
                'terjawab' => $terjawabDiBagian,
                'persentase' => $persentase,
            ];
        }

        return $progress;
    }

    /**
     * Calculate summary score counts (Total, A, B, C).
     *
     * @return array<string, int>
     */
    public function calculateScoreSummary(SuratPengajuan $surat): array
    {
        $jawaban = $surat->jawabanEvaluasi()->whereNotNull('skor')->get();

        return [
            'total' => $jawaban->count(),
            'skor_a' => $jawaban->where('skor', 'A')->count(),
            'skor_b' => $jawaban->where('skor', 'B')->count(),
            'skor_c' => $jawaban->where('skor', 'C')->count(),
        ];
    }
}

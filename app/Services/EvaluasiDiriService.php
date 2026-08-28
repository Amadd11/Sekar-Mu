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
            $payload = [];
            foreach (['skor', 'catatan', 'bukti', 'file_attachments', 'evidence_strength', 'pic_user_id'] as $key) {
                if (array_key_exists($key, $data)) {
                    $payload[$key] = $data[$key];
                }
            }

            return JawabanEvaluasi::updateOrCreate(
                [
                    'surat_pengajuan_id' => $surat->id,
                    'butir_evaluasi_id' => $butirId,
                ],
                $payload
            );
        });
    }

    /**
     * Calculate completion progress for each section (A to E).
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\BagianEvaluasi>|null  $bagianList  Preloaded sections to avoid redundant queries.
     * @return array<string, array<string, mixed>>
     */
    public function calculateProgress(SuratPengajuan $surat, $bagianList = null): array
    {
        $semuaBagian = $bagianList ?? BagianEvaluasi::with('butir')->orderBy('urutan')->get();
        $jawabanMap = $surat->jawabanEvaluasi()->get()->keyBy('butir_evaluasi_id');

        $progress = [];

        foreach ($semuaBagian as $bagian) {
            $allButir = $bagian->relationLoaded('butir')
                ? $bagian->butir
                : $bagian->butir()->get();

            $totalButir = $allButir->count();
            $lengkap = 0;
            $belumLengkap = 0;

            foreach ($allButir as $b) {
                $ans = $jawabanMap->get($b->id);
                $files = $ans ? $ans->getAttachments() : [];
                $count = count($files);

                if ($count >= 2) {
                    $lengkap++;
                } elseif ($count === 1) {
                    $belumLengkap++;
                }
            }

            $effective = $lengkap + ($belumLengkap * 0.5);
            $persentase = $totalButir > 0 ? (int) round(($effective / $totalButir) * 100) : 0;

            $progress[$bagian->kode] = [
                'nama' => $bagian->nama,
                'total' => $totalButir,
                'terjawab' => $lengkap,
                'belum_lengkap' => $belumLengkap,
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
            'skor_d' => $jawaban->where('skor', 'D')->count(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\BagianEvaluasi;
use App\Models\ButirEvaluasi;
use App\Models\KelompokEvaluasi;
use Illuminate\Database\Seeder;

class InstrumenEvaluasiSeeder extends Seeder
{
    /**
     * Seed instrument sections, groups, and standard question items.
     */
    public function run(): void
    {
        $dataBagian = [
            [
                'kode' => 'A',
                'nama' => 'Regulasi, Kelembagaan, dan Tata Kelola',
                'urutan' => 1,
                'kelompok' => [
                    [
                        'nama' => 'Organisasi dan Landasan Hukum KEPK',
                        'jumlah_butir' => 10,
                        'kritis' => [1, 2, 5],
                    ],
                    [
                        'nama' => 'Komposisi dan Kualifikasi Keanggotaan',
                        'jumlah_butir' => 10,
                        'kritis' => [1, 3, 7],
                    ],
                    [
                        'nama' => 'Independensi dan Konflik Kepentingan',
                        'jumlah_butir' => 9,
                        'kritis' => [1, 4],
                    ],
                ],
            ],
            [
                'kode' => 'B',
                'nama' => 'Keanggotaan dan Kompetensi',
                'urutan' => 2,
                'kelompok' => [
                    [
                        'nama' => 'Kualifikasi dan Pelatihan Anggota (GCP/Etik)',
                        'jumlah_butir' => 12,
                        'kritis' => [1, 3, 6],
                    ],
                    [
                        'nama' => 'Komposisi Multidisiplin dan Keterwakilan Gender/Lay Person',
                        'jumlah_butir' => 12,
                        'kritis' => [1, 5],
                    ],
                    [
                        'nama' => 'Prosedur Penunjukan dan Evaluasi Kinerja Anggota',
                        'jumlah_butir' => 11,
                        'kritis' => [2, 7],
                    ],
                ],
            ],
            [
                'kode' => 'C',
                'nama' => 'Operasional dan Prosedur',
                'urutan' => 3,
                'kelompok' => [
                    [
                        'nama' => 'Penerimaan dan Verifikasi Berkas Protokol',
                        'jumlah_butir' => 19,
                        'kritis' => [1, 5, 10],
                    ],
                    [
                        'nama' => 'Prosedur Sidang Full Board & Exempted/Expedited',
                        'jumlah_butir' => 19,
                        'kritis' => [1, 4, 12],
                    ],
                    [
                        'nama' => 'Kriteria Telaah 7 Standar Etik CIOMS/WHO',
                        'jumlah_butir' => 18,
                        'kritis' => [1, 3, 8, 15],
                    ],
                    [
                        'nama' => 'Pengambilan Keputusan & Dokumentasi Telaah',
                        'jumlah_butir' => 18,
                        'kritis' => [2, 6, 11],
                    ],
                ],
            ],
            [
                'kode' => 'D',
                'nama' => 'Fasilitas dan Sumber Daya',
                'urutan' => 4,
                'kelompok' => [
                    [
                        'nama' => 'Ruang Kerja dan Fasilitas Sekretariat',
                        'jumlah_butir' => 6,
                        'kritis' => [1, 3],
                    ],
                    [
                        'nama' => 'Sistem Informasi, Penyimpanan Aman, dan Backup Data',
                        'jumlah_butir' => 6,
                        'kritis' => [2, 5],
                    ],
                ],
            ],
            [
                'kode' => 'E',
                'nama' => 'Penelitian Khusus',
                'urutan' => 5,
                'kelompok' => [
                    [
                        'nama' => 'Penelitian Populasi Rentan dan Uji Klinis',
                        'jumlah_butir' => 7,
                        'kritis' => [1, 4],
                    ],
                    [
                        'nama' => 'Transfer Material Hayati dan Penelitian Genetik',
                        'jumlah_butir' => 7,
                        'kritis' => [2, 5],
                    ],
                ],
            ],
        ];

        $activeButirIds = [];

        foreach ($dataBagian as $bData) {
            $bagian = BagianEvaluasi::updateOrCreate(
                ['kode' => $bData['kode']],
                [
                    'nama' => $bData['nama'],
                    'urutan' => $bData['urutan'],
                ]
            );

            foreach ($bData['kelompok'] as $kIdx => $kData) {
                $kelompokUrutan = $kIdx + 1;
                $kelompok = KelompokEvaluasi::updateOrCreate(
                    [
                        'bagian_evaluasi_id' => $bagian->id,
                        'nama' => $kData['nama'],
                    ],
                    [
                        'urutan' => $kelompokUrutan,
                    ]
                );

                $kritisList = $kData['kritis'] ?? [];
                for ($i = 1; $i <= $kData['jumlah_butir']; $i++) {
                    $kodeItem = $bData['kode'] . $kelompokUrutan . '.' . $i;
                    $isCritical = in_array($i, $kritisList, true);

                    $bRecord = ButirEvaluasi::updateOrCreate(
                        [
                            'kelompok_evaluasi_id' => $kelompok->id,
                            'kode' => $kodeItem,
                        ],
                        [
                            'pertanyaan' => "Pemenuhan standar baku etik penelitian terkait " . strtolower($kData['nama']) . " (Kriteria parameter {$kodeItem}).",
                            'is_critical' => $isCritical,
                            'standar' => "Standar {$bData['kode']}",
                            'parameter' => "Parameter {$kodeItem}",
                            'evidence_required' => $isCritical
                                ? "Wajib melampirkan SK/SOP resmi, bukti implementasi, dan dokumentasi pendukung terverifikasi."
                                : "Dokumen SOP/Panduan/Logbook/Bukti pendukung pelaksanaan.",
                        ]
                    );

                    $activeButirIds[] = $bRecord->id;
                }
            }
        }

        ButirEvaluasi::whereNotIn('id', $activeButirIds)->delete();
    }
}

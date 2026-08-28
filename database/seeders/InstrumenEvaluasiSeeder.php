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
                    ],
                    [
                        'nama' => 'Komposisi dan Kualifikasi Keanggotaan',
                        'jumlah_butir' => 10,
                    ],
                    [
                        'nama' => 'Independensi dan Konflik Kepentingan',
                        'jumlah_butir' => 9,
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
                    ],
                    [
                        'nama' => 'Komposisi Multidisiplin dan Keterwakilan Gender/Lay Person',
                        'jumlah_butir' => 12,
                    ],
                    [
                        'nama' => 'Prosedur Penunjukan dan Evaluasi Kinerja Anggota',
                        'jumlah_butir' => 11,
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
                    ],
                    [
                        'nama' => 'Prosedur Sidang Full Board & Exempted/Expedited',
                        'jumlah_butir' => 19,
                    ],
                    [
                        'nama' => 'Kriteria Telaah 7 Standar Etik CIOMS/WHO',
                        'jumlah_butir' => 18,
                    ],
                    [
                        'nama' => 'Pengambilan Keputusan & Dokumentasi Telaah',
                        'jumlah_butir' => 18,
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
                    ],
                    [
                        'nama' => 'Sistem Informasi, Penyimpanan Aman, dan Backup Data',
                        'jumlah_butir' => 6,
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
                    ],
                    [
                        'nama' => 'Transfer Material Hayati dan Penelitian Genetik',
                        'jumlah_butir' => 7,
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

                for ($i = 1; $i <= $kData['jumlah_butir']; $i++) {
                    $kodeItem = $bData['kode'] . $kelompokUrutan . '.' . $i;

                    $bRecord = ButirEvaluasi::updateOrCreate(
                        [
                            'kelompok_evaluasi_id' => $kelompok->id,
                            'kode' => $kodeItem,
                        ],
                        [
                            'pertanyaan' => "Pemenuhan standar baku etik penelitian terkait " . strtolower($kData['nama']) . " (Kriteria parameter {$kodeItem}).",
                            'standar' => "Standar {$bData['kode']}",
                            'parameter' => "Parameter {$kodeItem}",
                            'evidence_required' => "Dokumen SOP/Panduan/Logbook/Bukti pendukung pelaksanaan.",
                        ]
                    );

                    $activeButirIds[] = $bRecord->id;
                }
            }
        }

        ButirEvaluasi::whereNotIn('id', $activeButirIds)->delete();
    }
}

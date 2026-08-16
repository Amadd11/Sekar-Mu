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
                'nama' => 'Struktur dan Komposisi KEP',
                'urutan' => 1,
                'target_butir' => 20,
                'kelompok' => [
                    'Organisasi dan Landasan Hukum KEPK',
                    'Komposisi dan Kualifikasi Keanggotaan',
                    'Independensi dan Konflik Kepentingan',
                ],
            ],
            [
                'kode' => 'B',
                'nama' => 'Kepatuhan terhadap Kebijakan Khusus',
                'urutan' => 2,
                'target_butir' => 35,
                'kelompok' => [
                    'Kebijakan Riset Populasi Rentan',
                    'Kebijakan Uji Klinis & Intervensi',
                    'Informed Consent & Perlindungan Data Subjek',
                ],
            ],
            [
                'kode' => 'C',
                'nama' => 'Kelengkapan Proses Telaah',
                'urutan' => 3,
                'target_butir' => 74,
                'kelompok' => [
                    'Penerimaan dan Verifikasi Berkas Protokol',
                    'Prosedur Sidang Full Board & Exempted/Expedited',
                    'Kriteria Telaah 7 Standar Etik CIOMS/WHO',
                ],
            ],
            [
                'kode' => 'D',
                'nama' => 'Setelah Proses Peninjauan',
                'urutan' => 4,
                'target_butir' => 12,
                'kelompok' => [
                    'Komunikasi Keputusan & Surat Persetujuan Etik',
                    'Pemantauan Berkelanjutan & Laporan Kejadian Tak Diinginkan (SAE)',
                ],
            ],
            [
                'kode' => 'E',
                'nama' => 'Dokumentasi dan Pengarsipan',
                'urutan' => 5,
                'target_butir' => 14,
                'kelompok' => [
                    'Manajemen Arsip Berkas Protokol',
                    'Sistem Keamanan dan Kerahasiaan Rekam Dokumen',
                ],
            ],
        ];

        foreach ($dataBagian as $bData) {
            $bagian = BagianEvaluasi::firstOrCreate(
                ['kode' => $bData['kode']],
                [
                    'nama' => $bData['nama'],
                    'urutan' => $bData['urutan'],
                ]
            );

            $kelompokList = [];
            foreach ($bData['kelompok'] as $kIdx => $kNama) {
                $kelompokList[] = KelompokEvaluasi::firstOrCreate(
                    [
                        'bagian_evaluasi_id' => $bagian->id,
                        'nama' => $kNama,
                    ],
                    [
                        'urutan' => $kIdx + 1,
                    ]
                );
            }

            $targetCount = $bData['target_butir'];
            $currentCount = $bagian->butir()->count();

            if ($currentCount < $targetCount) {
                $jmlKelompok = count($kelompokList);
                for ($i = $currentCount + 1; $i <= $targetCount; $i++) {
                    $kelompok = $kelompokList[($i - 1) % $jmlKelompok];

                    ButirEvaluasi::firstOrCreate(
                        [
                            'kelompok_evaluasi_id' => $kelompok->id,
                            'pertanyaan' => "Butir {$bData['kode']}.{$i} — Kelengkapan pemenuhan standar etik untuk " . strtolower($kelompok->nama) . " sub-elemen ke-{$i}.",
                        ],
                        [
                            'urutan' => $i,
                        ]
                    );
                }
            }
        }
    }
}

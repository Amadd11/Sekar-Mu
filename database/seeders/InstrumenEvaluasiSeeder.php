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
                'target_butir' => 29,
                'kelompok' => [
                    'Organisasi dan Landasan Hukum KEPK',
                    'Komposisi dan Kualifikasi Keanggotaan',
                    'Independensi dan Konflik Kepentingan',
                ],
                'critical_items' => [1, 2, 5, 8, 12, 18], // SK pembentukan, independensi, COI, pakta kerahasiaan
            ],
            [
                'kode' => 'B',
                'nama' => 'Keanggotaan dan Kompetensi',
                'urutan' => 2,
                'target_butir' => 35,
                'kelompok' => [
                    'Kualifikasi dan Pelatihan Anggota (GCP/Etik)',
                    'Komposisi Multidisiplin dan Keterwakilan Gender/Lay Person',
                    'Prosedur Penunjukan dan Evaluasi Kinerja Anggota',
                ],
                'critical_items' => [1, 4, 10, 15, 22], // Sertifikat GCP, komposisi minimum, lay person
            ],
            [
                'kode' => 'C',
                'nama' => 'Operasional dan Prosedur',
                'urutan' => 3,
                'target_butir' => 74,
                'kelompok' => [
                    'Penerimaan dan Verifikasi Berkas Protokol',
                    'Prosedur Sidang Full Board & Exempted/Expedited',
                    'Kriteria Telaah 7 Standar Etik CIOMS/WHO',
                    'Pengambilan Keputusan & Dokumentasi Telaah',
                ],
                'critical_items' => [1, 3, 7, 14, 20, 28, 35, 42, 50, 65], // SOP telaah, proses telaah etik, informed consent, SAE
            ],
            [
                'kode' => 'D',
                'nama' => 'Fasilitas dan Sumber Daya',
                'urutan' => 4,
                'target_butir' => 12,
                'kelompok' => [
                    'Ruang Kerja dan Fasilitas Sekretariat',
                    'Sistem Informasi, Penyimpanan Aman, dan Backup Data',
                ],
                'critical_items' => [2, 6, 9], // Ruang arsip aman, backup database, kerahasiaan data
            ],
            [
                'kode' => 'E',
                'nama' => 'Penelitian Khusus',
                'urutan' => 5,
                'target_butir' => 14,
                'kelompok' => [
                    'Penelitian Populasi Rentan dan Uji Klinis',
                    'Transfer Material Hayati dan Penelitian Genetik',
                ],
                'critical_items' => [1, 5, 8], // Perlindungan populasi rentan, persetujuan MTA
            ],
        ];

        foreach ($dataBagian as $bData) {
            $bagian = BagianEvaluasi::updateOrCreate(
                ['kode' => $bData['kode']],
                [
                    'nama' => $bData['nama'],
                    'urutan' => $bData['urutan'],
                ]
            );

            $kelompokList = [];
            foreach ($bData['kelompok'] as $kIdx => $kNama) {
                $kelompokList[] = KelompokEvaluasi::updateOrCreate(
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
            $jmlKelompok = count($kelompokList);
            $criticalIndexes = $bData['critical_items'] ?? [];

            for ($i = 1; $i <= $targetCount; $i++) {
                $kelompok = $kelompokList[($i - 1) % $jmlKelompok];
                $isCritical = in_array($i, $criticalIndexes, true);

                ButirEvaluasi::updateOrCreate(
                    [
                        'kelompok_evaluasi_id' => $kelompok->id,
                        'urutan' => $i,
                    ],
                    [
                        'pertanyaan' => "Butir {$bData['kode']}.{$i} — Pemenuhan standar baku etik penelitian terkait " . strtolower($kelompok->nama) . " (Kriteria parameter {$i}).",
                        'is_critical' => $isCritical,
                        'standar' => "Standar {$bData['kode']}",
                        'parameter' => "Parameter {$bData['kode']}.{$i}",
                        'evidence_required' => $isCritical
                            ? "Wajib melampirkan SK/SOP resmi, bukti implementasi, dan dokumentasi pendukung terverifikasi."
                            : "Dokumen SOP/Panduan/Logbook/Bukti pendukung pelaksanaan.",
                    ]
                );
            }
        }
    }
}

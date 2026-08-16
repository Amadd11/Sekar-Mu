<?php

namespace Database\Seeders;

use App\Models\AssessmentGroup;
use App\Models\AssessmentItem;
use App\Models\AssessmentSection;
use Illuminate\Database\Seeder;

class AssessmentInstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'code' => 'A',
                'name' => 'Struktur dan Komposisi KEP',
                'order' => 1,
                'target_count' => 20,
                'groups' => [
                    'Organisasi dan Landasan Hukum KEPK',
                    'Komposisi dan Kualifikasi Keanggotaan',
                    'Independensi dan Konflik Kepentingan',
                ],
            ],
            [
                'code' => 'B',
                'name' => 'Kepatuhan terhadap Kebijakan Khusus',
                'order' => 2,
                'target_count' => 35,
                'groups' => [
                    'Kebijakan Riset Populasi Rentan',
                    'Kebijakan Uji Klinis & Intervensi',
                    'Informed Consent & Perlindungan Data Subjek',
                ],
            ],
            [
                'code' => 'C',
                'name' => 'Kelengkapan Proses Telaah',
                'order' => 3,
                'target_count' => 74,
                'groups' => [
                    'Penerimaan dan Verifikasi Berkas Protokol',
                    'Prosedur Sidang Full Board & Exempted/Expedited',
                    'Kriteria Telaah 7 Standar Etik CIOMS/WHO',
                ],
            ],
            [
                'code' => 'D',
                'name' => 'Setelah Proses Peninjauan',
                'order' => 4,
                'target_count' => 12,
                'groups' => [
                    'Komunikasi Keputusan & Surat Persetujuan Etik',
                    'Pemantauan Berkelanjutan & Laporan Kejadian Tak Diinginkan (SAE)',
                ],
            ],
            [
                'code' => 'E',
                'name' => 'Dokumentasi dan Pengarsipan',
                'order' => 5,
                'target_count' => 14,
                'groups' => [
                    'Manajemen Arsip Berkas Protokol',
                    'Sistem Keamanan dan Kerahasiaan Rekam Dokumen',
                ],
            ],
        ];

        foreach ($sections as $secData) {
            $section = AssessmentSection::firstOrCreate(
                ['code' => $secData['code']],
                [
                    'name' => $secData['name'],
                    'order' => $secData['order'],
                ]
            );

            $groups = [];
            foreach ($secData['groups'] as $gIdx => $gName) {
                $groups[] = AssessmentGroup::firstOrCreate(
                    [
                        'assessment_section_id' => $section->id,
                        'name' => $gName,
                    ],
                    [
                        'order' => $gIdx + 1,
                    ]
                );
            }

            // Generate items up to target_count
            $targetCount = $secData['target_count'];
            $currentCount = $section->items()->count();

            if ($currentCount < $targetCount) {
                $groupCount = count($groups);
                for ($i = $currentCount + 1; $i <= $targetCount; $i++) {
                    $group = $groups[($i - 1) % $groupCount];

                    AssessmentItem::firstOrCreate(
                        [
                            'assessment_group_id' => $group->id,
                            'question' => "Butir {$secData['code']}.{$i} — Kelengkapan pemenuhan standar etik untuk " . strtolower($group->name) . " sub-elemen ke-{$i}.",
                        ],
                        [
                            'order' => $i,
                        ]
                    );
                }
            }
        }
    }
}

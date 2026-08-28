<?php

namespace App\Livewire\Pengajuan;

use App\Models\BagianEvaluasi;
use App\Models\SuratPengajuan;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dokumen extends Component
{
    public SuratPengajuan $suratPengajuan;

    public string $activeSection = 'A';

    public string $search = '';

    public string $filterStatus = 'all'; // all, lengkap (>=2), belum_lengkap (1)

    protected $queryString = [
        'activeSection' => ['except' => 'A'],
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'all'],
    ];

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan;
    }

    public function setSection(string $kode): void
    {
        $this->activeSection = $kode;
    }

    public function resetSearch(): void
    {
        $this->search = '';
    }

    public function render(): View
    {
        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $jawabanMap = $this->suratPengajuan->jawabanEvaluasi()
            ->whereNotNull('file_attachments')
            ->get()
            ->keyBy('butir_evaluasi_id');

        $totalFilesAll = 0;
        $totalButirWithFilesAll = 0;
        $fileCountsPerBagian = [];
        $butirCountsPerBagian = [];
        $allSectionsData = [];

        $searchTerm = trim(strtolower($this->search));

        foreach ($bagianList as $bagian) {
            $itemsWithFiles = [];
            $sectionFilesCount = 0;
            $sectionButirCount = 0;

            foreach ($bagian->kelompok as $kelompok) {
                foreach ($kelompok->butir as $butir) {
                    $ans = $jawabanMap->get($butir->id);
                    $attachments = $ans ? $ans->getAttachments() : [];
                    $attCount = count($attachments);

                    if ($attCount > 0) {
                        $sectionFilesCount += $attCount;
                        $sectionButirCount++;
                        $totalFilesAll += $attCount;
                        $totalButirWithFilesAll++;

                        // Apply Status Filter
                        if ($this->filterStatus === 'lengkap' && $attCount < 2) {
                            continue;
                        }
                        if ($this->filterStatus === 'belum_lengkap' && $attCount !== 1) {
                            continue;
                        }

                        // Apply Search Filter (on file name, butir code, question, or SK/bukti)
                        if ($searchTerm !== '') {
                            $matchesCode = str_contains(strtolower($butir->kode ?? ''), $searchTerm);
                            $matchesQuestion = str_contains(strtolower($butir->pertanyaan ?? ''), $searchTerm);
                            $matchesBukti = str_contains(strtolower($ans->bukti ?? ''), $searchTerm);
                            $matchesFiles = false;

                            foreach ($attachments as $att) {
                                if (str_contains(strtolower($att['name'] ?? ''), $searchTerm)) {
                                    $matchesFiles = true;
                                    break;
                                }
                            }

                            if (! $matchesCode && ! $matchesQuestion && ! $matchesBukti && ! $matchesFiles) {
                                continue;
                            }
                        }

                        $itemsWithFiles[] = [
                            'butir_id' => $butir->id,
                            'kode' => $butir->kode ?? ('#' . $butir->id),
                            'pertanyaan' => $butir->pertanyaan,
                            'standar' => $butir->standar ?? 'Standar',
                            'kelompok_nama' => $kelompok->nama,
                            'bukti' => $ans->bukti ?? null,
                            'catatan' => $ans->catatan ?? null,
                            'attachments' => array_map(function ($att) {
                                $path = $att['path'] ?? '';
                                return [
                                    'name' => $att['name'] ?? 'Dokumen',
                                    'path' => $path,
                                    'url' => \Illuminate\Support\Facades\Storage::url($path),
                                    'size' => (int) ($att['size'] ?? 0),
                                    'size_formatted' => format_bytes((int) ($att['size'] ?? 0)),
                                    'is_pdf' => str_ends_with(strtolower($path), '.pdf') || str_ends_with(strtolower($att['name'] ?? ''), '.pdf'),
                                    'is_image' => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'], true),
                                ];
                            }, $attachments),
                            'count' => $attCount,
                        ];
                    }
                }
            }

            $fileCountsPerBagian[$bagian->kode] = $sectionFilesCount;
            $butirCountsPerBagian[$bagian->kode] = $sectionButirCount;

            $allSectionsData[$bagian->kode] = [
                'bagian' => $bagian,
                'items' => $itemsWithFiles,
                'total_files' => $sectionFilesCount,
                'total_butir' => $sectionButirCount,
            ];
        }

        $activeBagianModel = $bagianList->firstWhere('kode', $this->activeSection) ?? $bagianList->first();

        return view('livewire.pengajuan.dokumen', [
            'bagianList' => $bagianList,
            'activeBagianModel' => $activeBagianModel,
            'allSectionsData' => $allSectionsData,
            'activeSectionData' => $allSectionsData[$this->activeSection] ?? ['items' => [], 'total_files' => 0, 'total_butir' => 0],
            'totalFilesAll' => $totalFilesAll,
            'totalButirWithFilesAll' => $totalButirWithFilesAll,
            'fileCountsPerBagian' => $fileCountsPerBagian,
            'butirCountsPerBagian' => $butirCountsPerBagian,
        ])->layout('layouts.app');
    }
}

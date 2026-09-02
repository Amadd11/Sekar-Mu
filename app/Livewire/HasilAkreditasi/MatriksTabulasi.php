<?php

namespace App\Livewire\HasilAkreditasi;

use App\Models\BagianEvaluasi;
use App\Models\SuratPengajuan;
use App\Services\ComplianceService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class MatriksTabulasi extends Component
{
    public SuratPengajuan $suratPengajuan;

    #[Url(as: 'bagian')]
    public string $filterBagian = '';

    #[Url(as: 'nilai')]
    public string $filterNilai = '';

    #[Url(as: 'bukti')]
    public string $filterBukti = '';

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $user = auth()->user();
        if ($user && $user->isAsessor() && ! $user->isAdmin()) {
            abort(403, 'Akses terbatas. Matriks tabulasi rekapitulasi tidak dapat diakses oleh akun asesor.');
        }

        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load([
            'kepk.institusi',
            'formulirAplikasi',
            'profilKepk',
            'jawabanEvaluasi',
            'penilaianButirAsesor',
            'penilai',
        ]);
    }

    public function resetFilter(): void
    {
        $this->reset(['filterBagian', 'filterNilai', 'filterBukti', 'search']);
    }

    public function render(ComplianceService $complianceService): View
    {
        $this->suratPengajuan->load(['jawabanEvaluasi', 'penilaianButirAsesor']);

        $selfAnswers = $this->suratPengajuan->jawabanEvaluasi->keyBy('butir_evaluasi_id');
        $assessorScores = $this->suratPengajuan->penilaianButirAsesor->keyBy('butir_evaluasi_id');

        $allSections = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();

        $stats = [
            'total' => 0,
            'countA' => 0,
            'countB' => 0,
            'countC' => 0,
            'unscored' => 0,
            'buktiLengkap' => 0,
            'buktiSebagian' => 0,
            'buktiKosong' => 0,
        ];

        // First pass: compute overall stats from all sections before filtering
        foreach ($allSections as $sec) {
            foreach ($sec->kelompok as $kel) {
                foreach ($kel->butir as $b) {
                    $stats['total']++;

                    $ans = $selfAnswers->get($b->id);
                    $ass = $assessorScores->get($b->id);
                    $score = $ass?->skor ?? $ans?->skor;

                    if ($score === 'A') {
                        $stats['countA']++;
                    } elseif ($score === 'B') {
                        $stats['countB']++;
                    } elseif ($score === 'C') {
                        $stats['countC']++;
                    } else {
                        $stats['unscored']++;
                    }

                    $fileCount = $ans ? count($ans->getAttachments()) : 0;
                    if ($fileCount >= 2) {
                        $stats['buktiLengkap']++;
                    } elseif ($fileCount === 1) {
                        $stats['buktiSebagian']++;
                    } else {
                        $stats['buktiKosong']++;
                    }
                }
            }
        }

        // Second pass: filter matrix data according to user filter
        $searchTerm = trim(strtolower($this->search));
        $filteredSections = [];

        foreach ($allSections as $sec) {
            if ($this->filterBagian && $sec->kode !== $this->filterBagian) {
                continue;
            }

            $sectionGroupData = [];

            foreach ($sec->kelompok as $kel) {
                $groupItems = [];

                foreach ($kel->butir as $b) {
                    $ans = $selfAnswers->get($b->id);
                    $ass = $assessorScores->get($b->id);
                    $score = $ass?->skor ?? $ans?->skor;
                    $attachments = $ans ? $ans->getAttachments() : [];
                    $fileCount = count($attachments);

                    // Filter Nilai
                    if ($this->filterNilai) {
                        if ($this->filterNilai === 'unscored' && ! empty($score)) {
                            continue;
                        }
                        if ($this->filterNilai !== 'unscored' && $score !== $this->filterNilai) {
                            continue;
                        }
                    }

                    // Filter Bukti
                    if ($this->filterBukti) {
                        if ($this->filterBukti === 'lengkap' && $fileCount < 2) {
                            continue;
                        }
                        if ($this->filterBukti === 'belum_lengkap' && $fileCount !== 1) {
                            continue;
                        }
                        if ($this->filterBukti === 'kosong' && $fileCount > 0) {
                            continue;
                        }
                    }

                    // Filter Search
                    if ($searchTerm !== '') {
                        $matchKode = str_contains(strtolower($b->kode ?? ''), $searchTerm);
                        $matchTanya = str_contains(strtolower($b->pertanyaan ?? ''), $searchTerm);
                        $matchStandar = str_contains(strtolower($b->standar ?? ''), $searchTerm);
                        $matchBukti = str_contains(strtolower($ans->bukti ?? ''), $searchTerm);
                        $matchCatatan = str_contains(strtolower($ans->catatan ?? ''), $searchTerm) || str_contains(strtolower($ass->catatan ?? ''), $searchTerm) || str_contains(strtolower($ass->temuan ?? ''), $searchTerm);

                        if (! $matchKode && ! $matchTanya && ! $matchStandar && ! $matchBukti && ! $matchCatatan) {
                            continue;
                        }
                    }

                    $groupItems[] = [
                        'butir' => $b,
                        'jawaban' => $ans,
                        'penilaian' => $ass,
                        'skor' => $score,
                        'attachments' => $attachments,
                        'fileCount' => $fileCount,
                    ];
                }

                if (! empty($groupItems)) {
                    $sectionGroupData[] = [
                        'kelompok' => $kel,
                        'items' => $groupItems,
                    ];
                }
            }

            if (! empty($sectionGroupData)) {
                $filteredSections[] = [
                    'bagian' => $sec,
                    'kelompokList' => $sectionGroupData,
                ];
            }
        }

        $metrics = $complianceService->calculateComplianceMetrics($this->suratPengajuan);

        return view('livewire.hasil-akreditasi.matriks-tabulasi', [
            'filteredSections' => $filteredSections,
            'allSections' => $allSections,
            'stats' => $stats,
            'metrics' => $metrics,
        ])->layout('layouts.app');
    }
}

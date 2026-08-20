<?php

namespace App\Livewire\Penilaian;

use App\Models\BagianEvaluasi;
use App\Models\CatatanPenilaian;
use App\Models\CorrectiveAction;
use App\Models\PenilaianButirAsesor;
use App\Models\PenilaianEtik as PenilaianEtikModel;
use App\Models\SuratPengajuan;
use App\Services\ComplianceService;
use App\Services\CorrectiveActionService;
use App\Services\PenilaianService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LembarPenilaian extends Component
{
    public SuratPengajuan $suratPengajuan;

    public string $activeTab = 'asesmen_butir'; // asesmen_butir, ringkasan, matriks_gap, corrective_actions
    public string $activeSection = 'A';

    public string $rekomendasi = 'approved';
    public string $catatan = '';
    public string $catatanBaru = '';

    /**
     * @var array<int, string>
     */
    public array $itemSkor = [];

    /**
     * @var array<int, string>
     */
    public array $itemCatatan = [];

    /**
     * @var array<int, string>
     */
    public array $itemTemuan = [];

    /**
     * @var array<int, string>
     */
    public array $evidenceStrength = [];

    public ?PenilaianEtikModel $currentPenilaian = null;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'rekomendasi' => ['required', 'string', 'in:approved,revision_required,rejected'],
            'catatan' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $user = auth()->user();
        if (! $user->isAdmin() && (! $user->isReviewer() || ! $suratPengajuan->penilai()->where('user_id', $user->id)->exists())) {
            abort(403, 'Akses terbatas untuk penilai yang ditugaskan atau administrator.');
        }

        $this->suratPengajuan = $suratPengajuan->load([
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'listProtokol',
            'dokumen.pengunggah',
            'jawabanEvaluasi.butir',
            'penilaianEtik.penilai',
            'penilaianEtik.catatanPenilaian.user',
            'penilaianButirAsesor',
            'correctiveActions.butir',
        ]);

        $this->currentPenilaian = $this->suratPengajuan->penilaianEtik()
            ->where('penilai_id', auth()->id())
            ->first();

        if ($this->currentPenilaian) {
            $this->rekomendasi = $this->currentPenilaian->rekomendasi;
            $this->catatan = $this->currentPenilaian->catatan ?? '';
        }

        // Load assessor item assessments
        $myItemAssessments = PenilaianButirAsesor::where('surat_pengajuan_id', $suratPengajuan->id)
            ->where('penilai_id', auth()->id())
            ->get();

        foreach ($myItemAssessments as $ass) {
            $this->itemSkor[$ass->butir_evaluasi_id] = $ass->skor ?? '';
            $this->itemCatatan[$ass->butir_evaluasi_id] = $ass->catatan ?? '';
            $this->itemTemuan[$ass->butir_evaluasi_id] = $ass->temuan ?? '';
            $this->evidenceStrength[$ass->butir_evaluasi_id] = $ass->evidence_strength ?? '';
        }
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function switchSection(string $section): void
    {
        $this->activeSection = $section;
    }

    public function nextSection(): void
    {
        $sections = ['A', 'B', 'C', 'D', 'E'];
        $currentIndex = array_search($this->activeSection, $sections, true);
        if ($currentIndex !== false && $currentIndex < count($sections) - 1) {
            $this->activeSection = $sections[$currentIndex + 1];
        }
    }

    public function previousSection(): void
    {
        $sections = ['A', 'B', 'C', 'D', 'E'];
        $currentIndex = array_search($this->activeSection, $sections, true);
        if ($currentIndex !== false && $currentIndex > 0) {
            $this->activeSection = $sections[$currentIndex - 1];
        }
    }

    public function updatedItemCatatan($value, $key): void
    {
        $this->saveItemNotes((int) $key, app(PenilaianService::class));
    }

    public function updatedItemTemuan($value, $key): void
    {
        $this->saveItemNotes((int) $key, app(PenilaianService::class));
    }

    public function setItemSkor(int $butirId, string $skorValue, PenilaianService $service): void
    {
        $this->itemSkor[$butirId] = $skorValue;

        $service->saveItemAssessment(
            $this->suratPengajuan,
            auth()->user(),
            $butirId,
            [
                'skor' => $skorValue,
                'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
                'catatan' => $this->itemCatatan[$butirId] ?? null,
                'temuan' => $this->itemTemuan[$butirId] ?? null,
            ]
        );
    }

    public function setStrength(int $butirId, string $strengthValue, PenilaianService $service): void
    {
        $this->evidenceStrength[$butirId] = $strengthValue;

        $service->saveItemAssessment(
            $this->suratPengajuan,
            auth()->user(),
            $butirId,
            [
                'skor' => $this->itemSkor[$butirId] ?? null,
                'evidence_strength' => $strengthValue,
                'catatan' => $this->itemCatatan[$butirId] ?? null,
                'temuan' => $this->itemTemuan[$butirId] ?? null,
            ]
        );
    }

    public function saveItemNotes(int $butirId, PenilaianService $service): void
    {
        $service->saveItemAssessment(
            $this->suratPengajuan,
            auth()->user(),
            $butirId,
            [
                'skor' => $this->itemSkor[$butirId] ?? null,
                'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
                'catatan' => $this->itemCatatan[$butirId] ?? null,
                'temuan' => $this->itemTemuan[$butirId] ?? null,
            ]
        );
    }

    public function updateCorrectiveActionStatus(int $actionId, string $status, CorrectiveActionService $service, ?string $notes = null): void
    {
        $action = CorrectiveAction::findOrFail($actionId);
        $service->updateStatus($action, $status, $notes);
        $this->suratPengajuan->refresh();

        session()->flash('action_status', "Status tindakan perbaikan berhasil diubah menjadi {$status}.");
    }

    public function simpanPenilaian(PenilaianService $service): void
    {
        $this->validate();

        $this->currentPenilaian = $service->submitReview(
            $this->suratPengajuan,
            auth()->user(),
            [
                'rekomendasi' => $this->rekomendasi,
                'catatan' => $this->catatan,
            ]
        );

        $this->suratPengajuan->refresh();

        session()->flash('status', 'Hasil rekomendasi penilaian etik berhasil disimpan.');
    }

    public function kirimCatatan(PenilaianService $service): void
    {
        $this->validate(['catatanBaru' => 'required|string|max:2000']);

        if (! $this->currentPenilaian) {
            $this->currentPenilaian = $service->submitReview(
                $this->suratPengajuan,
                auth()->user(),
                [
                    'rekomendasi' => $this->rekomendasi,
                    'catatan' => $this->catatan,
                ]
            );
        }

        $service->addComment($this->currentPenilaian, auth()->user(), $this->catatanBaru);
        $this->catatanBaru = '';
        $this->suratPengajuan->refresh();

        session()->flash('comment_status', 'Catatan penilaian berhasil ditambahkan.');
    }

    public function toggleSelesai(int $catatanId, PenilaianService $service): void
    {
        $catatan = CatatanPenilaian::findOrFail($catatanId);
        $service->toggleResolveComment($catatan);

        $this->suratPengajuan->refresh();
    }

    public function render(ComplianceService $complianceService, PenilaianService $penilaianService): View
    {
        $this->suratPengajuan->load([
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'listProtokol',
            'dokumen.pengunggah',
            'jawabanEvaluasi.butir',
            'penilaianEtik.penilai',
            'penilaianEtik.catatanPenilaian.user',
            'penilaianButirAsesor',
            'correctiveActions.butir',
        ]);

        $metrics = $complianceService->calculateComplianceMetrics($this->suratPengajuan);
        $gapAnalysis = $complianceService->calculateGapAnalysis($this->suratPengajuan);
        $comparisonMatrix = $penilaianService->getComparisonMatrix($this->suratPengajuan, auth()->id());

        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $activeBagian = $bagianList->firstWhere('kode', $this->activeSection) ?? $bagianList->first();

        // Calculate progress for each section from assessor item assessments
        $sectionProgress = [];
        foreach ($bagianList as $bg) {
            $totalInSec = $bg->butir->count();
            $scoredInSec = PenilaianButirAsesor::where('surat_pengajuan_id', $this->suratPengajuan->id)
                ->where('penilai_id', auth()->id())
                ->whereIn('butir_evaluasi_id', $bg->butir->pluck('id'))
                ->whereNotNull('skor')
                ->count();

            $sectionProgress[$bg->kode] = [
                'total' => $totalInSec,
                'scored' => $scoredInSec,
                'pct' => $totalInSec > 0 ? (int) round(($scoredInSec / $totalInSec) * 100) : 0,
            ];
        }

        return view('livewire.penilaian.lembar-penilaian', [
            'metrics' => $metrics,
            'gapAnalysis' => $gapAnalysis,
            'comparisonMatrix' => $comparisonMatrix,
            'bagianList' => $bagianList,
            'activeBagian' => $activeBagian,
            'sectionProgress' => $sectionProgress,
            'semuaPenilaian' => $this->suratPengajuan->penilaianEtik()->with(['penilai', 'catatanPenilaian.user'])->get(),
            'correctiveActions' => $this->suratPengajuan->correctiveActions()->with('butir')->get(),
        ])->layout('layouts.app');
    }
}

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
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class LembarPenilaian extends Component
{
    /**
     * Urutan section penilaian. Dipakai bersama oleh switchSection,
     * nextSection, previousSection, dan progress bar.
     *
     * @var array<int, string>
     */
    private const SECTIONS = ['A', 'B', 'C', 'D', 'E'];

    public const VALID_SKOR = ['A', 'B', 'C', 'D'];
    public const VALID_STRENGTH = ['E0', 'E1', 'E2', 'E3', 'E4'];

    /**
     * Relasi yang selalu di-eager-load saat mount() dan render(),
     * supaya kedua tempat itu tidak bisa saling berbeda.
     *
     * @var array<int, string>
     */
    private const EAGER_RELATIONS = [
        'penilai',
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
    ];

    public SuratPengajuan $suratPengajuan;

    #[Locked]
    public bool $isAssigned = false;

    #[Url(as: 'tab')]
    public string $activeTab = 'penilaian'; // 'penilaian', 'dokumen', 'rekomendasi'

    #[Url(as: 'section')]
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
        if (! $user->isAdmin() && ! $user->isAsessor()) {
            abort(403, 'Akses terbatas untuk penilai etik dan administrator.');
        }

        $this->activeTab = $this->normalizeTab($this->activeTab);

        if (! in_array($this->activeSection, self::SECTIONS, true)) {
            $this->activeSection = self::SECTIONS[0];
        }

        $this->suratPengajuan = $suratPengajuan->load(self::EAGER_RELATIONS);

        $this->isAssigned = $user->isAdmin() || $this->suratPengajuan->penilai->contains('id', $user->id);

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

    /**
     * Normalisasi alias nama tab menjadi salah satu dari 3 tab kanonis.
     */
    private function normalizeTab(string $tab): string
    {
        return match (true) {
            in_array($tab, ['borang', 'penilaian'], true) => 'penilaian',
            in_array($tab, ['dokumen', 'berkas', 'protokol'], true) => 'dokumen',
            in_array($tab, ['rekomendasi', 'catatan'], true) => 'rekomendasi',
            default => 'penilaian',
        };
    }

    public function switchSection(string $section): void
    {
        if (in_array($section, self::SECTIONS, true)) {
            $this->activeSection = $section;
        }
    }

    public function nextSection(): void
    {
        $this->activeSection = $this->shiftSection(1);
    }

    public function previousSection(): void
    {
        $this->activeSection = $this->shiftSection(-1);
    }

    private function shiftSection(int $offset): string
    {
        $currentIndex = array_search($this->activeSection, self::SECTIONS, true);

        if ($currentIndex === false) {
            return $this->activeSection;
        }

        $targetIndex = $currentIndex + $offset;

        if ($targetIndex < 0 || $targetIndex >= count(self::SECTIONS)) {
            return $this->activeSection;
        }

        return self::SECTIONS[$targetIndex];
    }

    protected function ensureAssignedOrAdmin(): void
    {
        if (! $this->isAssigned && ! auth()->user()->isAdmin()) {
            abort(403, 'Anda belum ditugaskan untuk menelaah berkas permohonan ini.');
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
        $this->ensureAssignedOrAdmin();

        if (! in_array($skorValue, self::VALID_SKOR, true)) {
            return;
        }

        $this->itemSkor[$butirId] = $skorValue;

        $service->saveItemAssessment(
            $this->suratPengajuan,
            auth()->user(),
            $butirId,
            $this->itemAssessmentPayload($butirId, ['skor' => $skorValue])
        );
    }

    public function setStrength(int $butirId, string $strengthValue, PenilaianService $service): void
    {
        $this->ensureAssignedOrAdmin();

        if (! in_array($strengthValue, self::VALID_STRENGTH, true)) {
            return;
        }

        $this->evidenceStrength[$butirId] = $strengthValue;

        $service->saveItemAssessment(
            $this->suratPengajuan,
            auth()->user(),
            $butirId,
            $this->itemAssessmentPayload($butirId, ['evidence_strength' => $strengthValue])
        );
    }

    public function saveItemNotes(int $butirId, PenilaianService $service): void
    {
        $this->ensureAssignedOrAdmin();

        $service->saveItemAssessment(
            $this->suratPengajuan,
            auth()->user(),
            $butirId,
            $this->itemAssessmentPayload($butirId)
        );
    }

    /**
     * Bangun payload item assessment dari state komponen saat ini,
     * dengan opsi override untuk field yang baru saja diubah.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function itemAssessmentPayload(int $butirId, array $overrides = []): array
    {
        return array_merge([
            'skor' => $this->itemSkor[$butirId] ?? null,
            'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
            'catatan' => $this->itemCatatan[$butirId] ?? null,
            'temuan' => $this->itemTemuan[$butirId] ?? null,
        ], $overrides);
    }

    public function updateCorrectiveActionStatus(int $actionId, string $status, CorrectiveActionService $service, ?string $notes = null): void
    {
        $this->ensureAssignedOrAdmin();

        $action = CorrectiveAction::findOrFail($actionId);
        $service->updateStatus($action, $status, $notes);
        $this->suratPengajuan->refresh();

        session()->flash('action_status', "Status tindakan perbaikan berhasil diubah menjadi {$status}.");
    }

    public function simpanPenilaian(PenilaianService $service): void
    {
        $this->ensureAssignedOrAdmin();

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
        $this->ensureAssignedOrAdmin();

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
        $this->ensureAssignedOrAdmin();

        $catatan = CatatanPenilaian::findOrFail($catatanId);
        $service->toggleResolveComment($catatan);

        $this->suratPengajuan->refresh();
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $this->normalizeTab($tab);
    }

    public function render(ComplianceService $complianceService, PenilaianService $penilaianService): View
    {
        $this->suratPengajuan->load(self::EAGER_RELATIONS);

        $this->isAssigned = auth()->user()->isAdmin() || $this->suratPengajuan->penilai->contains('id', auth()->id());

        if (! $this->isAssigned && ! auth()->user()->isAdmin()) {
            return view('livewire.penilaian.lembar-penilaian', [
                'isAssigned' => false,
                'metrics' => [],
                'comparisonMatrix' => [],
                'bagianList' => collect(),
                'activeBagian' => null,
                'sectionProgress' => [],
                'semuaPenilaian' => collect(),
                'correctiveActions' => collect(),
            ])->layout('layouts.app');
        }

        $metrics = $complianceService->calculateComplianceMetrics($this->suratPengajuan);
        $comparisonMatrix = $penilaianService->getComparisonMatrix($this->suratPengajuan, auth()->id());

        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $activeBagian = $bagianList->firstWhere('kode', $this->activeSection) ?? $bagianList->first();

        $sectionProgress = $this->calculateSectionProgress($bagianList);

        return view('livewire.penilaian.lembar-penilaian', [
            'isAssigned' => $this->isAssigned,
            'metrics' => $metrics,
            'comparisonMatrix' => $comparisonMatrix,
            'bagianList' => $bagianList,
            'activeBagian' => $activeBagian,
            'sectionProgress' => $sectionProgress,
            'semuaPenilaian' => $this->suratPengajuan->penilaianEtik()->with(['penilai', 'catatanPenilaian.user'])->get(),
            'correctiveActions' => $this->suratPengajuan->correctiveActions()->with('butir')->get(),
        ])->layout('layouts.app');
    }

    private function calculateSectionProgress($bagianList): array
    {
        $sectionProgress = [];

        $myScoredButirIds = $this->suratPengajuan->penilaianButirAsesor
            ->where('penilai_id', auth()->id())
            ->whereNotNull('skor')
            ->pluck('butir_evaluasi_id')
            ->flip();

        foreach ($bagianList as $bg) {
            $totalInSec = $bg->butir->count();
            $scoredInSec = $bg->butir->filter(fn ($b) => isset($myScoredButirIds[$b->id]))->count();

            $sectionProgress[$bg->kode] = [
                'total' => $totalInSec,
                'scored' => $scoredInSec,
                'pct' => $totalInSec > 0 ? (int) round(($scoredInSec / $totalInSec) * 100) : 0,
            ];
        }

        return $sectionProgress;
    }
}

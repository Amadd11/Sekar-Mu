<?php

namespace App\Livewire\Pengajuan;

use App\Models\BagianEvaluasi;
use App\Models\SuratPengajuan;
use App\Services\EvaluasiDiriService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class EvaluasiDiri extends Component
{
    use WithFileUploads;

    /** @var list<string> Available evaluation sections. */
    private const SECTIONS = ['A', 'B', 'C', 'D', 'E'];

    public SuratPengajuan $suratPengajuan;

    #[Url(as: 'section')]
    public string $activeSection = 'A';

    /**
     * @var array<int, string>
     */
    public array $skor = [];

    /**
     * @var array<int, string>
     */
    public array $catatan = [];

    /**
     * @var array<int, string>
     */
    public array $bukti = [];

    /**
     * @var array<int, mixed>
     */
    public array $uploadedFiles = [];

    /**
     * @var array<int, string>
     */
    public array $evidenceStrength = [];

    /**
     * @var array<string, string>
     */
    public array $catatanUmum = [];

    /**
     * @var array<string, string>
     */
    public array $rekomendasiUmum = [];

    /**
     * @var array<string, string>
     */
    public array $dokumenStandar = [];

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan;

        foreach ($suratPengajuan->jawabanEvaluasi as $jwb) {
            $this->skor[$jwb->butir_evaluasi_id] = $jwb->skor ?? '';
            $this->catatan[$jwb->butir_evaluasi_id] = $jwb->catatan ?? '';
            $this->bukti[$jwb->butir_evaluasi_id] = $jwb->bukti ?? '';
            $this->evidenceStrength[$jwb->butir_evaluasi_id] = $jwb->evidence_strength ?? '';
        }
    }

    public function switchSection(string $code): void
    {
        $this->activeSection = $code;
    }

    public function nextSection(): void
    {
        $currentIndex = array_search($this->activeSection, self::SECTIONS, true);
        if ($currentIndex !== false && $currentIndex < count(self::SECTIONS) - 1) {
            $this->activeSection = self::SECTIONS[$currentIndex + 1];
        }
    }

    public function previousSection(): void
    {
        $currentIndex = array_search($this->activeSection, self::SECTIONS, true);
        if ($currentIndex !== false && $currentIndex > 0) {
            $this->activeSection = self::SECTIONS[$currentIndex - 1];
        }
    }

    public function updatedBukti($value, $key, EvaluasiDiriService $service): void
    {
        $this->simpanCatatan((int) $key, $service);
    }

    public function updatedCatatan($value, $key, EvaluasiDiriService $service): void
    {
        $this->simpanCatatan((int) $key, $service);
    }

    public function uploadBerkas(int $butirId, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        if (! isset($this->uploadedFiles[$butirId])) {
            return;
        }

        $rawFiles = $this->uploadedFiles[$butirId];
        $filesToUpload = is_array($rawFiles) ? $rawFiles : [$rawFiles];

        $this->validate([
            "uploadedFiles.{$butirId}" => ['required'],
            "uploadedFiles.{$butirId}.*" => ['file', 'max:25600', 'mimes:pdf,doc,docx,xls,xlsx,zip,jpg,jpeg,png'],
        ], [
            "uploadedFiles.{$butirId}.*.max" => 'Ukuran masing-masing berkas maksimal 25 MB.',
            "uploadedFiles.{$butirId}.*.mimes" => 'Format berkas harus PDF, Word, Excel, ZIP, atau Gambar.',
        ]);

        $ans = $this->suratPengajuan->jawabanEvaluasi()->where('butir_evaluasi_id', $butirId)->first();
        $attachments = $ans ? $ans->getAttachments() : [];

        $newNames = [];
        foreach ($filesToUpload as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $filename = $file->getClientOriginalName();
                $path = $file->store("pengajuan/{$this->suratPengajuan->id}/evaluasi", 'public');

                $attachments[] = [
                    'name' => $filename,
                    'path' => $path,
                    'size' => $file->getSize() ?: 0,
                ];
                $newNames[] = $filename;
            }
        }

        $autoBukti = ! empty($this->bukti[$butirId])
            ? $this->bukti[$butirId]
            : implode(', ', array_column($attachments, 'name'));

        $service->saveAnswer($this->suratPengajuan, $butirId, [
            'file_attachments' => $attachments,
            'bukti' => $autoBukti,
            'catatan' => $this->catatan[$butirId] ?? null,
            'skor' => $this->skor[$butirId] ?? null,
            'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
        ]);

        $this->bukti[$butirId] = $autoBukti;
        unset($this->uploadedFiles[$butirId]);
        $this->suratPengajuan->refresh();

        $count = count($newNames);
        session()->flash("status_{$butirId}", "Sebanyak {$count} berkas berhasil ditambahkan sebagai bukti dukung.");
    }

    public function hapusBerkas(int $butirId, ?int $fileIndex = null): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $ans = $this->suratPengajuan->jawabanEvaluasi()->where('butir_evaluasi_id', $butirId)->first();
        if (! $ans) {
            return;
        }

        $attachments = $ans->getAttachments();

        if ($fileIndex !== null && isset($attachments[$fileIndex])) {
            $deletedFile = $attachments[$fileIndex];
            if (! empty($deletedFile['path']) && Storage::disk('public')->exists($deletedFile['path'])) {
                Storage::disk('public')->delete($deletedFile['path']);
            }

            unset($attachments[$fileIndex]);
            $attachments = array_values($attachments);
        } else {
            foreach ($attachments as $att) {
                if (! empty($att['path']) && Storage::disk('public')->exists($att['path'])) {
                    Storage::disk('public')->delete($att['path']);
                }
            }
            $attachments = [];
        }

        $ans->update([
            'file_attachments' => ! empty($attachments) ? $attachments : null,
        ]);

        $this->suratPengajuan->refresh();
        session()->flash("status_{$butirId}", "Berkas lampiran bukti dukung berhasil diperbarui.");
    }

    public function setSkor(int $butirId, string $skorValue, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $this->skor[$butirId] = $skorValue;
        $service->saveAnswer($this->suratPengajuan, $butirId, $this->buildPayload($butirId));
    }

    public function setStrength(int $butirId, string $strengthValue, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $this->evidenceStrength[$butirId] = $strengthValue;
        $service->saveAnswer($this->suratPengajuan, $butirId, $this->buildPayload($butirId));
    }

    public function simpanCatatan(int $butirId, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $service->saveAnswer($this->suratPengajuan, $butirId, $this->buildPayload($butirId));
    }

    /**
     * Build a standardized payload array for saving an answer.
     *
     * @return array<string, mixed>
     */
    private function buildPayload(int $butirId): array
    {
        return [
            'skor' => $this->skor[$butirId] ?? null,
            'catatan' => $this->catatan[$butirId] ?? null,
            'bukti' => $this->bukti[$butirId] ?? null,
            'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
        ];
    }

    public function render(EvaluasiDiriService $service): View
    {
        $this->suratPengajuan->load(['jawabanEvaluasi', 'penilaianButirAsesor']);

        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $activeBagian = $bagianList->firstWhere('kode', $this->activeSection) ?? $bagianList->first();

        $progress = $service->calculateProgress($this->suratPengajuan, $bagianList);
        $rekapSkor = $service->calculateScoreSummary($this->suratPengajuan);
        $penilaianAsesor = $this->suratPengajuan->penilaianButirAsesor->keyBy('butir_evaluasi_id')->all();
        $jawabanMap = $this->suratPengajuan->jawabanEvaluasi->keyBy('butir_evaluasi_id')->all();

        // Pre-compute kelompok progress so Blade has no heavy PHP logic
        $kelompokProgress = [];
        if ($activeBagian) {
            foreach ($activeBagian->kelompok as $kelompok) {
                $butirList = $kelompok->butir;
                $total = $butirList->count();
                $filled = 0;

                foreach ($butirList as $b) {
                    $hasBukti = ! empty($this->bukti[$b->id] ?? null);
                    $hasCatatan = ! empty($this->catatan[$b->id] ?? null);
                    $hasFiles = isset($jawabanMap[$b->id]) && count($jawabanMap[$b->id]->getAttachments()) > 0;

                    if ($hasBukti || $hasCatatan || $hasFiles) {
                        $filled++;
                    }
                }

                $kelompokProgress[$kelompok->id] = [
                    'total' => $total,
                    'filled' => $filled,
                    'percentage' => $total > 0 ? (int) round(($filled / $total) * 100) : 0,
                ];
            }
        }

        return view('livewire.pengajuan.evaluasi-diri', [
            'bagianList' => $bagianList,
            'activeBagian' => $activeBagian,
            'progress' => $progress,
            'rekapSkor' => $rekapSkor,
            'penilaianAsesor' => $penilaianAsesor,
            'jawabanMap' => $jawabanMap,
            'kelompokProgress' => $kelompokProgress,
            'isEditable' => $this->suratPengajuan->isEditable(),
        ])->layout('layouts.app');
    }
}

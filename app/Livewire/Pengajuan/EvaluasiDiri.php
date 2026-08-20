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

        $this->suratPengajuan = $suratPengajuan->load('jawabanEvaluasi');

        foreach ($this->suratPengajuan->jawabanEvaluasi as $jwb) {
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

    public function updatedBukti($value, $key): void
    {
        $this->simpanCatatan((int) $key, app(EvaluasiDiriService::class));
    }

    public function updatedCatatan($value, $key): void
    {
        $this->simpanCatatan((int) $key, app(EvaluasiDiriService::class));
    }

    public function uploadBerkas(int $butirId, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $this->validate([
            "uploadedFiles.{$butirId}" => ['required', 'file', 'max:25600', 'mimes:pdf,doc,docx,xls,xlsx,zip,jpg,jpeg,png'],
        ], [
            "uploadedFiles.{$butirId}.max" => 'Ukuran berkas maksimal 25 MB.',
            "uploadedFiles.{$butirId}.mimes" => 'Format berkas harus PDF, Word, Excel, ZIP, atau Gambar.',
        ]);

        $file = $this->uploadedFiles[$butirId];
        $filename = $file->getClientOriginalName();
        $path = $file->store("pengajuan/{$this->suratPengajuan->id}/evaluasi", 'public');

        $service->saveAnswer($this->suratPengajuan, $butirId, [
            'file_path' => $path,
            'file_name' => $filename,
            'file_size' => $file->getSize(),
            'bukti' => !empty($this->bukti[$butirId]) ? $this->bukti[$butirId] : $filename,
            'catatan' => $this->catatan[$butirId] ?? null,
        ]);

        $this->bukti[$butirId] = !empty($this->bukti[$butirId]) ? $this->bukti[$butirId] : $filename;
        unset($this->uploadedFiles[$butirId]);
        $this->suratPengajuan->refresh();

        session()->flash("status_{$butirId}", "Berkas '{$filename}' berhasil diunggah.");
    }

    public function hapusBerkas(int $butirId): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $ans = $this->suratPengajuan->jawabanEvaluasi()->where('butir_evaluasi_id', $butirId)->first();
        if ($ans && $ans->file_path) {
            Storage::disk('public')->delete($ans->file_path);
            $ans->update([
                'file_path' => null,
                'file_name' => null,
                'file_size' => null,
            ]);
        }

        $this->suratPengajuan->refresh();
        session()->flash("status_{$butirId}", "Berkas lampiran butir berhasil dihapus.");
    }

    public function setSkor(int $butirId, string $skorValue, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $this->skor[$butirId] = $skorValue;

        $service->saveAnswer($this->suratPengajuan, $butirId, [
            'skor' => $skorValue,
            'catatan' => $this->catatan[$butirId] ?? null,
            'bukti' => $this->bukti[$butirId] ?? null,
            'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
        ]);
    }

    public function setStrength(int $butirId, string $strengthValue, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $this->evidenceStrength[$butirId] = $strengthValue;

        $service->saveAnswer($this->suratPengajuan, $butirId, [
            'skor' => $this->skor[$butirId] ?? null,
            'catatan' => $this->catatan[$butirId] ?? null,
            'bukti' => $this->bukti[$butirId] ?? null,
            'evidence_strength' => $strengthValue,
        ]);
    }

    public function simpanCatatan(int $butirId, EvaluasiDiriService $service): void
    {
        if (! $this->suratPengajuan->isEditable()) {
            return;
        }

        $service->saveAnswer($this->suratPengajuan, $butirId, [
            'skor' => $this->skor[$butirId] ?? null,
            'catatan' => $this->catatan[$butirId] ?? null,
            'bukti' => $this->bukti[$butirId] ?? null,
            'evidence_strength' => $this->evidenceStrength[$butirId] ?? null,
        ]);
    }

    public function render(EvaluasiDiriService $service): View
    {
        $this->suratPengajuan->load(['jawabanEvaluasi', 'penilaianButirAsesor']);

        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $activeBagian = $bagianList->firstWhere('kode', $this->activeSection) ?? $bagianList->first();

        $progress = $service->calculateProgress($this->suratPengajuan);
        $rekapSkor = $service->calculateScoreSummary($this->suratPengajuan);
        $penilaianAsesor = $this->suratPengajuan->penilaianButirAsesor->keyBy('butir_evaluasi_id');

        return view('livewire.pengajuan.evaluasi-diri', [
            'bagianList' => $bagianList,
            'activeBagian' => $activeBagian,
            'progress' => $progress,
            'rekapSkor' => $rekapSkor,
            'penilaianAsesor' => $penilaianAsesor,
        ])->layout('layouts.app');
    }
}

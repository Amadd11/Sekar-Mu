<?php

namespace App\Livewire\Pengajuan;

use App\Models\BagianEvaluasi;
use App\Models\SuratPengajuan;
use App\Services\EvaluasiDiriService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EvaluasiDiri extends Component
{
    public SuratPengajuan $suratPengajuan;

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

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load('jawabanEvaluasi');

        foreach ($this->suratPengajuan->jawabanEvaluasi as $jwb) {
            $this->skor[$jwb->butir_evaluasi_id] = $jwb->skor ?? '';
            $this->catatan[$jwb->butir_evaluasi_id] = $jwb->catatan ?? '';
            $this->bukti[$jwb->butir_evaluasi_id] = $jwb->bukti ?? '';
        }
    }

    public function switchSection(string $code): void
    {
        $this->activeSection = $code;
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
        ]);
    }

    public function render(EvaluasiDiriService $service): View
    {
        $bagianList = BagianEvaluasi::with(['kelompok.butir'])->orderBy('urutan')->get();
        $activeBagian = $bagianList->firstWhere('kode', $this->activeSection) ?? $bagianList->first();

        $progress = $service->calculateProgress($this->suratPengajuan);
        $rekapSkor = $service->calculateScoreSummary($this->suratPengajuan);

        return view('livewire.pengajuan.evaluasi-diri', [
            'bagianList' => $bagianList,
            'activeBagian' => $activeBagian,
            'progress' => $progress,
            'rekapSkor' => $rekapSkor,
        ])->layout('layouts.app');
    }
}

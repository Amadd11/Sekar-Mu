<?php

namespace App\Livewire\Penilaian;

use App\Models\CatatanPenilaian;
use App\Models\PenilaianEtik as PenilaianEtikModel;
use App\Models\SuratPengajuan;
use App\Services\EvaluasiDiriService;
use App\Services\PenilaianService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LembarPenilaian extends Component
{
    public SuratPengajuan $suratPengajuan;

    public string $rekomendasi = 'approved';
    public string $catatan = '';
    public string $catatanBaru = '';

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
        $this->authorize('view', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load([
            'formulirAplikasi',
            'profilKepk',
            'anggotaKepk',
            'listProtokol',
            'dokumen.pengunggah',
            'jawabanEvaluasi.butir',
            'penilaianEtik.penilai',
            'penilaianEtik.catatanPenilaian.user',
        ]);

        $this->currentPenilaian = $this->suratPengajuan->penilaianEtik()
            ->where('penilai_id', auth()->id())
            ->first();

        if ($this->currentPenilaian) {
            $this->rekomendasi = $this->currentPenilaian->rekomendasi;
            $this->catatan = $this->currentPenilaian->catatan ?? '';
        }
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

    public function render(EvaluasiDiriService $evaluasiService): View
    {
        $progress = $evaluasiService->calculateProgress($this->suratPengajuan);
        $rekapSkor = $evaluasiService->calculateScoreSummary($this->suratPengajuan);

        return view('livewire.penilaian.lembar-penilaian', [
            'progress' => $progress,
            'rekapSkor' => $rekapSkor,
            'semuaPenilaian' => $this->suratPengajuan->penilaianEtik()->with(['penilai', 'catatanPenilaian.user'])->get(),
        ])->layout('layouts.app');
    }
}

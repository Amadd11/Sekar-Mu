<?php

namespace App\Livewire\Penilaian;

use App\Models\SuratPengajuan;
use App\Models\User;
use App\Services\PenilaianService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TugaskanPenilai extends Component
{
    public SuratPengajuan $suratPengajuan;

    /**
     * @var list<int>
     */
    public array $selectedReviewerIds = [];

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat menugaskan penilai.');
        }

        $this->suratPengajuan = $suratPengajuan->load(['penilai', 'formulirAplikasi', 'kepk.institusi']);
        $this->selectedReviewerIds = $this->suratPengajuan->penilai->pluck('id')->toArray();
    }

    public function save(PenilaianService $service): void
    {
        $this->validate([
            'selectedReviewerIds' => ['required', 'array', 'min:1'],
            'selectedReviewerIds.*' => ['exists:users,id'],
        ]);

        $service->assignReviewers($this->suratPengajuan, $this->selectedReviewerIds, auth()->user());
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Penilai etik berhasil ditugaskan ke permohonan ini.');
    }

    public function render(): View
    {
        $daftarReviewer = User::role('reviewer')->get();

        return view('livewire.penilaian.tugaskan-penilai', [
            'daftarReviewer' => $daftarReviewer,
        ])->layout('layouts.app');
    }
}

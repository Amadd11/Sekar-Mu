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

    public string $search = '';

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator yang memiliki wewenang menugaskan asesor.');
        }

        $this->suratPengajuan = $suratPengajuan->load(['penilai', 'formulirAplikasi', 'kepk.institusi']);
        $this->selectedReviewerIds = $this->suratPengajuan->penilai->pluck('id')->toArray();
    }

    public function toggleReviewer(int $userId): void
    {
        if (in_array($userId, $this->selectedReviewerIds)) {
            $this->selectedReviewerIds = array_values(array_diff($this->selectedReviewerIds, [$userId]));
        } else {
            $this->selectedReviewerIds[] = $userId;
        }
    }

    public function selectAll(): void
    {
        $this->selectedReviewerIds = User::role('asessor')->pluck('id')->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedReviewerIds = [];
    }

    public function save(PenilaianService $service): void
    {
        $this->validate([
            'selectedReviewerIds' => ['required', 'array', 'min:1'],
            'selectedReviewerIds.*' => ['exists:users,id'],
        ], [
            'selectedReviewerIds.required' => 'Pilih minimal satu orang asesor untuk ditugaskan.',
            'selectedReviewerIds.min' => 'Pilih minimal satu orang asesor untuk ditugaskan.',
        ]);

        $service->assignReviewers($this->suratPengajuan, $this->selectedReviewerIds, auth()->user());
        $this->suratPengajuan->refresh();

        session()->flash('status', 'Tim asesor penilai independen berhasil ditugaskan ke permohonan akreditasi ini.');
    }

    public function render(): View
    {
        $query = User::role('asessor')
            ->withCount([
                'pengajuanDinilai as beban_aktif_count' => function ($q) {
                    $q->where('status', SuratPengajuan::STATUS_IN_PROGRESS);
                }
            ]);

        if (trim($this->search) !== '') {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $daftarReviewer = $query->orderBy('name')->get();

        return view('livewire.penilaian.tugaskan-penilai', [
            'daftarReviewer' => $daftarReviewer,
        ])->layout('layouts.app');
    }
}

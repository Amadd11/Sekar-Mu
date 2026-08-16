<?php

namespace App\Livewire\Reviews;

use App\Models\Application;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AssignReviewers extends Component
{
    public Application $application;

    /**
     * @var list<int>
     */
    public array $selectedReviewerIds = [];

    public function mount(Application $application): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat menugaskan penelaah.');
        }

        $this->application = $application->load(['assignedReviewers', 'information', 'kepk.institution']);
        $this->selectedReviewerIds = $this->application->assignedReviewers->pluck('id')->toArray();
    }

    public function save(ReviewService $service): void
    {
        $this->validate([
            'selectedReviewerIds' => ['required', 'array', 'min:1'],
            'selectedReviewerIds.*' => ['exists:users,id'],
        ]);

        $service->assignReviewers($this->application, $this->selectedReviewerIds, auth()->user());
        $this->application->refresh();

        session()->flash('status', 'Penelaah etik berhasil ditugaskan.');
    }

    public function removeReviewer(int $reviewerId, ReviewService $service): void
    {
        $service->removeReviewer($this->application, $reviewerId);
        $this->selectedReviewerIds = array_values(array_diff($this->selectedReviewerIds, [$reviewerId]));
        $this->application->refresh();

        session()->flash('status', 'Penelaah etik berhasil dihapus dari penugasan.');
    }

    public function render(): View
    {
        $availableReviewers = User::role('reviewer')->get();

        return view('livewire.reviews.assign-reviewers', [
            'availableReviewers' => $availableReviewers,
        ])->layout('layouts.app');
    }
}

<?php

namespace App\Livewire\Reviews;

use App\Models\Application;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function mount(): void
    {
        if (! auth()->user()->isReviewer() && ! auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses ke portal penelaah.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $user = auth()->user();

        $query = Application::query()
            ->with(['kepk.institution', 'information', 'assignedReviewers', 'reviews'])
            ->whereIn('status', ['submitted', 'under_review', 'revision_required', 'resubmitted', 'approved', 'rejected']);

        if ($user->isReviewer() && ! $user->isAdmin()) {
            $query->whereHas('assignedReviewers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('information', function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('city', 'like', "%{$this->search}%");
                })->orWhere('id', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.reviews.index', [
            'applications' => $query->latest('submitted_at')->paginate(10),
        ])->layout('layouts.app');
    }
}

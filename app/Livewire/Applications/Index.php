<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = Application::query()
            ->with(['kepk.institution', 'information'])
            ->latest();

        // Applicants only see their own applications
        if ($user->isApplicant()) {
            $query->where('user_id', $user->id);
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->whereHas('information', function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('city', 'like', "%{$this->search}%");
                })->orWhereHas('kepk', function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%");
                });
            });
        }

        $applications = $query->paginate(10);

        return view('livewire.applications.index', [
            'applications' => $applications,
            'statuses' => Application::STATUSES,
        ])->layout('layouts.app');
    }
}

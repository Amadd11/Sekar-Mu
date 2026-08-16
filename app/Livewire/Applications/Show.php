<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Services\ApplicationService;
use App\Services\ReviewService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public Application $application;

    public function mount(Application $application): void
    {
        $this->authorize('view', $application);

        $this->application = $application->load([
            'kepk.institution',
            'information',
            'profile',
            'members',
            'protocols',
            'documents.uploader',
            'assignedReviewers',
            'reviews.reviewer',
            'user',
        ]);
    }

    public function submitApplication(ApplicationService $service): void
    {
        $this->authorize('submit', $this->application);

        if (! $this->application->information || empty($this->application->information->name)) {
            session()->flash('error', 'Mohon lengkapi nama institusi pada formulir Informasi sebelum mengajukan.');

            return;
        }

        $service->submit($this->application);
        $this->application->refresh();

        session()->flash('status', 'Pengajuan etik berhasil diajukan untuk ditelaah!');
    }

    public function resubmitApplication(ApplicationService $service): void
    {
        $this->authorize('submit', $this->application);

        $service->submit($this->application);
        $this->application->refresh();

        session()->flash('status', 'Berkas perbaikan revisi berhasil diajukan ulang!');
    }

    public function finalizeDecision(string $decision, ReviewService $reviewService): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang berwenang menetapkan status akhir.');
        }

        $reviewService->finalizeDecision($this->application, $decision);
        $this->application->refresh();

        session()->flash('status', 'Keputusan akhir permohonan etik berhasil ditetapkan: ' . Application::statusLabel($decision));
    }

    public function deleteApplication(ApplicationService $service)
    {
        $this->authorize('delete', $this->application);

        $service->delete($this->application);

        session()->flash('status', 'Draft pengajuan etik berhasil dihapus.');

        return $this->redirect(route('applications.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.applications.show')->layout('layouts.app');
    }
}

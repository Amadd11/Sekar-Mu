<?php

namespace App\Livewire\Applications;

use App\Http\Requests\Application\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Kepk;
use App\Services\ApplicationService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?int $kepk_id = null;
    public string $name = '';
    public string $abbreviation = '';
    public string $address = '';
    public string $city = '';
    public string $phone = '';
    public string $email = '';
    public string $description = '';
    public string $vision = '';
    public string $mission = '';

    public function mount(): void
    {
        $this->authorize('create', Application::class);

        // Pre-select first KEPK if available
        $firstKepk = Kepk::first();
        if ($firstKepk) {
            $this->kepk_id = $firstKepk->id;
        }
    }

    public function rules(): array
    {
        return (new StoreApplicationRequest())->rules();
    }

    public function save(ApplicationService $service)
    {
        $validated = $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $application = $service->createDraft($user, array_merge($validated, [
            'description' => $this->description,
            'vision' => $this->vision,
            'mission' => $this->mission,
        ]));

        session()->flash('status', 'Draft pengajuan etik berhasil dibuat.');

        return $this->redirect(route('applications.information', $application), navigate: true);
    }

    public function render(): View
    {
        $kepks = Kepk::with('institution')->where('status', 'active')->get();

        return view('livewire.applications.create', [
            'kepks' => $kepks,
        ])->layout('layouts.app');
    }
}

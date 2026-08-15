<?php

namespace App\Livewire\Applications;

use App\Http\Requests\Application\UpdateApplicationInformationRequest;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Information extends Component
{
    public Application $application;

    public string $name = '';
    public string $abbreviation = '';
    public string $address = '';
    public string $city = '';
    public string $phone = '';
    public string $email = '';

    public function mount(Application $application): void
    {
        $this->authorize('update', $application);

        $this->application = $application->load('information');

        if ($application->information) {
            $this->name = $application->information->name ?? '';
            $this->abbreviation = $application->information->abbreviation ?? '';
            $this->address = $application->information->address ?? '';
            $this->city = $application->information->city ?? '';
            $this->phone = $application->information->phone ?? '';
            $this->email = $application->information->email ?? '';
        }
    }

    public function rules(): array
    {
        return (new UpdateApplicationInformationRequest())->rules();
    }

    public function save(ApplicationService $service)
    {
        $this->authorize('update', $this->application);

        $validated = $this->validate();

        $service->updateInformation($this->application, $validated);

        session()->flash('status', 'Informasi pengajuan berhasil disimpan.');

        return $this->redirect(route('applications.profile', $this->application), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.applications.information')->layout('layouts.app');
    }
}

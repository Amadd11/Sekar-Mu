<?php

namespace App\Livewire\Applications;

use App\Http\Requests\Application\StoreApplicationMemberRequest;
use App\Http\Requests\Application\UpdateApplicationProfileRequest;
use App\Models\Application;
use App\Models\ApplicationMember;
use App\Services\ApplicationService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Profile extends Component
{
    public Application $application;

    public string $description = '';
    public string $vision = '';
    public string $mission = '';

    // Member form fields
    public string $new_member_name = '';
    public string $new_member_position = '';
    public string $new_member_email = '';
    public string $new_member_phone = '';

    public function mount(Application $application): void
    {
        $this->authorize('update', $application);

        $this->application = $application->load(['profile', 'members']);

        if ($application->profile) {
            $this->description = $application->profile->description ?? '';
            $this->vision = $application->profile->vision ?? '';
            $this->mission = $application->profile->mission ?? '';
        }
    }

    public function rules(): array
    {
        return (new UpdateApplicationProfileRequest())->rules();
    }

    public function saveProfile(ApplicationService $service): void
    {
        $this->authorize('update', $this->application);

        $validated = $this->validate();

        $service->updateProfile($this->application, $validated);

        session()->flash('status', 'Profil KEPK berhasil diperbarui.');
    }

    public function addMember(ApplicationService $service): void
    {
        $this->authorize('update', $this->application);

        $validatedMember = $this->validate([
            'new_member_name' => ['required', 'string', 'max:255'],
            'new_member_position' => ['nullable', 'string', 'max:100'],
            'new_member_email' => ['nullable', 'email', 'max:255'],
            'new_member_phone' => ['nullable', 'string', 'max:50'],
        ], [], [
            'new_member_name' => 'Nama Anggota',
            'new_member_position' => 'Jabatan',
            'new_member_email' => 'Email',
            'new_member_phone' => 'No. Telepon',
        ]);

        $service->addMember($this->application, [
            'name' => $validatedMember['new_member_name'],
            'position' => $validatedMember['new_member_position'],
            'email' => $validatedMember['new_member_email'],
            'phone' => $validatedMember['new_member_phone'],
        ]);

        $this->reset(['new_member_name', 'new_member_position', 'new_member_email', 'new_member_phone']);
        $this->application->load('members');

        session()->flash('member_status', 'Anggota KEPK berhasil ditambahkan.');
    }

    public function deleteMember(int $memberId, ApplicationService $service): void
    {
        $this->authorize('update', $this->application);

        $member = ApplicationMember::where('application_id', $this->application->id)
            ->findOrFail($memberId);

        $service->removeMember($member);
        $this->application->load('members');

        session()->flash('member_status', 'Anggota KEPK berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.applications.profile')->layout('layouts.app');
    }
}

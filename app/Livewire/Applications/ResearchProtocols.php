<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\ResearchProtocol;
use App\Services\ResearchProtocolService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ResearchProtocols extends Component
{
    public Application $application;

    public string $protocol_number = '';
    public string $title = '';
    public string $principal_investigator = '';
    public string $submission_date = '';
    public string $status = 'draft';

    public ?int $editingProtocolId = null;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'protocol_number' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'principal_investigator' => ['required', 'string', 'max:255'],
            'submission_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function mount(Application $application): void
    {
        $this->authorize('view', $application);

        $this->application = $application->load(['protocols', 'information']);
        $this->submission_date = now()->toDateString();
    }

    public function save(ResearchProtocolService $service): void
    {
        $this->authorize('update', $this->application);
        $this->validate();

        if ($this->editingProtocolId) {
            $protocol = ResearchProtocol::findOrFail($this->editingProtocolId);
            $service->update($protocol, [
                'protocol_number' => $this->protocol_number,
                'title' => $this->title,
                'principal_investigator' => $this->principal_investigator,
                'submission_date' => $this->submission_date,
                'status' => $this->status,
            ]);
            session()->flash('status', 'Protokol penelitian berhasil diperbarui.');
        } else {
            $service->create($this->application, [
                'protocol_number' => $this->protocol_number,
                'title' => $this->title,
                'principal_investigator' => $this->principal_investigator,
                'submission_date' => $this->submission_date,
                'status' => $this->status,
            ]);
            session()->flash('status', 'Protokol penelitian berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->application->refresh();
    }

    public function edit(int $protocolId): void
    {
        $protocol = ResearchProtocol::findOrFail($protocolId);
        $this->editingProtocolId = $protocol->id;
        $this->protocol_number = $protocol->protocol_number;
        $this->title = $protocol->title;
        $this->principal_investigator = $protocol->principal_investigator;
        $this->submission_date = $protocol->submission_date ? $protocol->submission_date->toDateString() : '';
        $this->status = $protocol->status;
    }

    public function delete(int $protocolId, ResearchProtocolService $service): void
    {
        $this->authorize('update', $this->application);

        $protocol = ResearchProtocol::findOrFail($protocolId);
        $service->delete($protocol);

        $this->application->refresh();
        session()->flash('status', 'Protokol penelitian berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->editingProtocolId = null;
        $this->protocol_number = '';
        $this->title = '';
        $this->principal_investigator = '';
        $this->submission_date = now()->toDateString();
        $this->status = 'draft';
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.applications.research-protocols', [
            'protocols' => $this->application->protocols()->latest()->get(),
        ])->layout('layouts.app');
    }
}

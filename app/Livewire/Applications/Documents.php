<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Documents extends Component
{
    use WithFileUploads;

    public Application $application;

    /**
     * @var \Illuminate\Http\UploadedFile|null
     */
    public $file = null;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip,jpg,jpeg,png', 'max:20480'],
        ];
    }

    public function mount(Application $application): void
    {
        $this->authorize('view', $application);

        $this->application = $application->load(['documents.uploader', 'information']);
    }

    public function upload(DocumentService $service): void
    {
        $this->authorize('update', $this->application);
        $this->validate();

        if ($this->file) {
            $service->upload($this->application, auth()->user(), $this->file);
            $this->reset('file');
            $this->application->refresh();

            session()->flash('status', 'Berkas dokumen berhasil diunggah.');
        }
    }

    public function delete(int $documentId, DocumentService $service): void
    {
        $this->authorize('update', $this->application);

        $document = Document::findOrFail($documentId);
        $service->delete($document);

        $this->application->refresh();
        session()->flash('status', 'Berkas dokumen berhasil dihapus.');
    }

    public function download(int $documentId): StreamedResponse
    {
        $this->authorize('view', $this->application);

        $document = Document::findOrFail($documentId);

        return Storage::disk('public')->download($document->path, $document->original_name);
    }

    public function render(): View
    {
        return view('livewire.applications.documents', [
            'documents' => $this->application->documents()->with('uploader')->latest()->get(),
        ])->layout('layouts.app');
    }
}

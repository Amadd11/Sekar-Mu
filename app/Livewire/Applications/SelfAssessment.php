<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\AssessmentSection;
use App\Services\SelfAssessmentService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SelfAssessment extends Component
{
    public Application $application;
    public string $activeSectionCode = 'A';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $answers = [];

    public function mount(Application $application): void
    {
        $this->authorize('view', $application);

        $this->application = $application->load([
            'answers',
            'information',
            'kepk.institution',
        ]);

        $this->loadAnswers();
    }

    public function loadAnswers(): void
    {
        $existing = $this->application->answers()->get();
        foreach ($existing as $ans) {
            $this->answers[$ans->assessment_item_id] = [
                'score' => $ans->score,
                'comment' => $ans->comment ?? '',
                'evidence' => $ans->evidence ?? '',
            ];
        }
    }

    public function selectSection(string $code): void
    {
        $this->activeSectionCode = $code;
    }

    public function saveItem(int $itemId, SelfAssessmentService $service): void
    {
        $this->authorize('update', $this->application);

        $itemData = $this->answers[$itemId] ?? [];

        $service->saveAnswer($this->application, $itemId, [
            'score' => $itemData['score'] ?? null,
            'comment' => $itemData['comment'] ?? null,
            'evidence' => $itemData['evidence'] ?? null,
        ]);

        $this->application->refresh();

        session()->flash("saved_{$itemId}", 'Tersimpan');
    }

    public function render(SelfAssessmentService $service): View
    {
        $sections = AssessmentSection::with(['groups.items'])->orderBy('order')->get();
        $currentSection = $sections->firstWhere('code', $this->activeSectionCode) ?? $sections->first();

        $progress = $service->calculateProgress($this->application);
        $scoreSummary = $service->calculateScoreSummary($this->application);

        return view('livewire.applications.self-assessment', [
            'sections' => $sections,
            'currentSection' => $currentSection,
            'progress' => $progress,
            'scoreSummary' => $scoreSummary,
        ])->layout('layouts.app');
    }
}

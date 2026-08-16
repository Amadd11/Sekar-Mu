<?php

namespace App\Livewire\Reviews;

use App\Models\Application;
use App\Models\Review as ReviewModel;
use App\Models\ReviewComment;
use App\Services\ReviewService;
use App\Services\SelfAssessmentService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Review extends Component
{
    public Application $application;

    public string $recommendation = 'approved';
    public string $notes = '';
    public string $newComment = '';

    public ?ReviewModel $currentReview = null;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'recommendation' => ['required', 'string', 'in:approved,revision_required,rejected'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function mount(Application $application): void
    {
        $this->authorize('view', $application);

        $this->application = $application->load([
            'information',
            'profile',
            'members',
            'protocols',
            'documents.uploader',
            'answers.item',
            'reviews.reviewer',
            'reviews.comments.user',
        ]);

        $this->currentReview = $this->application->reviews()
            ->where('reviewer_id', auth()->id())
            ->first();

        if ($this->currentReview) {
            $this->recommendation = $this->currentReview->recommendation;
            $this->notes = $this->currentReview->notes ?? '';
        }
    }

    public function submitReview(ReviewService $service): void
    {
        $this->validate();

        $this->currentReview = $service->submitReview(
            $this->application,
            auth()->user(),
            [
                'recommendation' => $this->recommendation,
                'notes' => $this->notes,
            ]
        );

        $this->application->refresh();

        session()->flash('status', 'Hasil rekomendasi telaah etik berhasil disimpan.');
    }

    public function postComment(ReviewService $service): void
    {
        $this->validate(['newComment' => 'required|string|max:2000']);

        if (! $this->currentReview) {
            $this->currentReview = $service->submitReview(
                $this->application,
                auth()->user(),
                [
                    'recommendation' => $this->recommendation,
                    'notes' => $this->notes,
                ]
            );
        }

        $service->addComment($this->currentReview, auth()->user(), $this->newComment);
        $this->newComment = '';
        $this->application->refresh();

        session()->flash('comment_status', 'Catatan telaah berhasil ditambahkan.');
    }

    public function toggleResolve(int $commentId, ReviewService $service): void
    {
        $comment = ReviewComment::findOrFail($commentId);
        $service->toggleResolveComment($comment);

        $this->application->refresh();
    }

    public function render(SelfAssessmentService $selfAssessmentService): View
    {
        $progress = $selfAssessmentService->calculateProgress($this->application);
        $scoreSummary = $selfAssessmentService->calculateScoreSummary($this->application);

        return view('livewire.reviews.review', [
            'progress' => $progress,
            'scoreSummary' => $scoreSummary,
            'allReviews' => $this->application->reviews()->with(['reviewer', 'comments.user'])->get(),
        ])->layout('layouts.app');
    }
}

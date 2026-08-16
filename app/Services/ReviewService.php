<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationReviewer;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * Assign reviewers to an application and set status to 'under_review'.
     *
     * @param  list<int>  $reviewerIds
     */
    public function assignReviewers(Application $application, array $reviewerIds, User $assignedBy): void
    {
        DB::transaction(function () use ($application, $reviewerIds, $assignedBy) {
            foreach ($reviewerIds as $reviewerId) {
                ApplicationReviewer::firstOrCreate(
                    [
                        'application_id' => $application->id,
                        'user_id' => $reviewerId,
                    ],
                    [
                        'assigned_by' => $assignedBy->id,
                        'assigned_at' => now(),
                    ]
                );
            }

            if (in_array($application->status, ['submitted', 'resubmitted'], true)) {
                $application->update(['status' => 'under_review']);
            }
        });
    }

    /**
     * Remove a reviewer assignment from an application.
     */
    public function removeReviewer(Application $application, int $reviewerId): void
    {
        DB::transaction(function () use ($application, $reviewerId) {
            ApplicationReviewer::where('application_id', $application->id)
                ->where('user_id', $reviewerId)
                ->delete();
        });
    }

    /**
     * Submit or update a review recommendation by a reviewer.
     *
     * @param  array<string, mixed>  $data
     */
    public function submitReview(Application $application, User $reviewer, array $data): Review
    {
        return DB::transaction(function () use ($application, $reviewer, $data) {
            $review = Review::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'reviewer_id' => $reviewer->id,
                ],
                [
                    'recommendation' => $data['recommendation'],
                    'notes' => $data['notes'] ?? null,
                    'decision_date' => now()->toDateString(),
                ]
            );

            // If recommendation is revision_required, update application status
            if ($data['recommendation'] === 'revision_required') {
                $application->update(['status' => 'revision_required']);
            }

            return $review;
        });
    }

    /**
     * Add a comment/item review note.
     */
    public function addComment(Review $review, User $user, string $comment): ReviewComment
    {
        return DB::transaction(function () use ($review, $user, $comment) {
            return ReviewComment::create([
                'review_id' => $review->id,
                'user_id' => $user->id,
                'comment' => $comment,
                'is_resolved' => false,
            ]);
        });
    }

    /**
     * Toggle resolve state of a review comment.
     */
    public function toggleResolveComment(ReviewComment $comment): void
    {
        DB::transaction(function () use ($comment) {
            $comment->update([
                'is_resolved' => ! $comment->is_resolved,
            ]);
        });
    }

    /**
     * Finalize the committee's decision on the application.
     */
    public function finalizeDecision(Application $application, string $decision, ?string $notes = null): Application
    {
        return DB::transaction(function () use ($application, $decision) {
            $application->update([
                'status' => $decision,
            ]);

            return $application->fresh();
        });
    }
}

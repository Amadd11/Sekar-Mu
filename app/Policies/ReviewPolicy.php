<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReviewer() || $user->isAdmin();
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $application->assignedReviewers()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function review(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $application->assignedReviewers()->where('user_id', $user->id)->exists()
                && in_array($application->status, ['submitted', 'under_review', 'resubmitted'], true);
        }

        return false;
    }

    public function assign(User $user): bool
    {
        return $user->isAdmin();
    }
}

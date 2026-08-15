<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isApplicant() && $user->id === $application->user_id) {
            return true;
        }

        // Reviewers can view if assigned
        if ($user->isReviewer()) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin();
    }

    public function update(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $application->user_id && $application->isEditable();
    }

    public function delete(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $application->user_id && $application->isDraft();
    }

    public function submit(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $application->user_id && $application->isEditable();
    }
}

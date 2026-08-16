<?php

namespace App\Policies;

use App\Models\ResearchProtocol;
use App\Models\User;

class ResearchProtocolPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ResearchProtocol $protocol): bool
    {
        if ($user->isAdmin() || $user->isReviewer()) {
            return true;
        }

        return $user->id === $protocol->application->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin();
    }

    public function update(User $user, ResearchProtocol $protocol): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $protocol->application->user_id && $protocol->application->isEditable();
    }

    public function delete(User $user, ResearchProtocol $protocol): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $protocol->application->user_id && $protocol->application->isEditable();
    }
}

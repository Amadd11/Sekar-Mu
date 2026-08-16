<?php

namespace App\Policies;

use App\Models\ListProtokol;
use App\Models\User;

class ListProtokolPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ListProtokol $protokol): bool
    {
        if ($user->isAdmin() || $user->isReviewer()) {
            return true;
        }

        return $user->id === $protokol->suratPengajuan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin();
    }

    public function update(User $user, ListProtokol $protokol): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $protokol->suratPengajuan->user_id && $protokol->suratPengajuan->isEditable();
    }

    public function delete(User $user, ListProtokol $protokol): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $protokol->suratPengajuan->user_id && $protokol->suratPengajuan->isEditable();
    }
}

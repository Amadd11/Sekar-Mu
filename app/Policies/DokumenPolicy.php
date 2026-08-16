<?php

namespace App\Policies;

use App\Models\Dokumen;
use App\Models\User;

class DokumenPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Dokumen $dokumen): bool
    {
        if ($user->isAdmin() || $user->isReviewer()) {
            return true;
        }

        return $user->id === $dokumen->suratPengajuan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin();
    }

    public function delete(User $user, Dokumen $dokumen): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $dokumen->suratPengajuan->user_id && $dokumen->suratPengajuan->isEditable();
    }
}

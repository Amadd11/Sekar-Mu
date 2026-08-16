<?php

namespace App\Policies;

use App\Models\SuratPengajuan;
use App\Models\User;

class SuratPengajuanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin() || $user->isReviewer()) {
            return true;
        }

        return $user->id === $surat->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin();
    }

    public function update(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $surat->user_id && $surat->isEditable();
    }

    public function delete(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $surat->user_id && $surat->isDraft();
    }

    public function submit(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $surat->user_id && $surat->isEditable();
    }
}

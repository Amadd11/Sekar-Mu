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
        if ($user->isAdmin() || $user->isReviewer() || $user->isApplicant()) {
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

        if ($user->isApplicant() && $surat->isEditable()) {
            return true;
        }

        return $user->id === $surat->user_id && $surat->isEditable();
    }

    public function delete(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (($user->isKetuaKepk() || $user->id === $surat->user_id) && $surat->isDraft()) {
            return true;
        }

        return false;
    }

    public function submit(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (($user->isKetuaKepk() || $user->id === $surat->user_id) && $surat->isEditable()) {
            return true;
        }

        return false;
    }
}

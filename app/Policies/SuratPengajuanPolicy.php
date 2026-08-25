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
        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $surat->penilai()->where('user_id', $user->id)->exists();
        }

        return $user->id === $surat->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk();
    }

    public function update(User $user, SuratPengajuan $surat): bool
    {
        if (! $surat->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk() || $user->id === $surat->user_id) {
            return true;
        }

        return false;
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
        if (! $surat->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->id === $surat->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can make a final status decision on the application.
     */
    public function decide(User $user, SuratPengajuan $surat): bool
    {
        return $user->isAdmin() && $surat->status !== 'draft';
    }
}

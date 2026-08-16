<?php

namespace App\Policies;

use App\Models\SuratPengajuan;
use App\Models\User;

class PenilaianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReviewer() || $user->isAdmin();
    }

    public function view(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $surat->penilai()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function review(User $user, SuratPengajuan $surat): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $surat->penilai()->where('user_id', $user->id)->exists()
                && in_array($surat->status, ['submitted', 'under_review', 'resubmitted'], true);
        }

        return false;
    }

    public function assign(User $user): bool
    {
        return $user->isAdmin();
    }
}

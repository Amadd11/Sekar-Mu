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
        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $dokumen->suratPengajuan->penilai()->where('user_id', $user->id)->exists();
        }

        return $user->id === $dokumen->suratPengajuan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk();
    }

    public function delete(User $user, Dokumen $dokumen): bool
    {
        if (! $dokumen->suratPengajuan->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk() || $user->id === $dokumen->suratPengajuan->user_id) {
            return true;
        }

        return false;
    }
}

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
        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota() || $user->isAsessor()) {
            return true;
        }

        return $user->id === $dokumen->suratPengajuan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota();
    }

    public function delete(User $user, Dokumen $dokumen): bool
    {
        if (! $dokumen->suratPengajuan->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota() || $user->id === $dokumen->suratPengajuan->user_id) {
            return true;
        }

        return false;
    }
}

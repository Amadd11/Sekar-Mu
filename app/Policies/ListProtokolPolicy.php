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
        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk()) {
            return true;
        }

        if ($user->isReviewer()) {
            return $protokol->suratPengajuan->penilai()->where('user_id', $user->id)->exists();
        }

        return $user->id === $protokol->suratPengajuan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isApplicant() || $user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk();
    }

    public function update(User $user, ListProtokol $protokol): bool
    {
        if (! $protokol->suratPengajuan->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk() || $user->id === $protokol->suratPengajuan->user_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ListProtokol $protokol): bool
    {
        if (! $protokol->suratPengajuan->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggotaKepk() || $user->id === $protokol->suratPengajuan->user_id) {
            return true;
        }

        return false;
    }
}

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
        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota() || $user->isAsessor()) {
            return true;
        }

        return $user->id === $protokol->suratPengajuan->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota();
    }

    public function update(User $user, ListProtokol $protokol): bool
    {
        if (! $protokol->suratPengajuan->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota() || $user->id === $protokol->suratPengajuan->user_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ListProtokol $protokol): bool
    {
        if (! $protokol->suratPengajuan->isEditable()) {
            return false;
        }

        if ($user->isAdmin() || $user->isKetuaKepk() || $user->isAnggota() || $user->id === $protokol->suratPengajuan->user_id) {
            return true;
        }

        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\SuratPengajuan;
use App\Models\User;

class PenilaianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAsessor() || $user->isAdmin();
    }

    public function view(User $user, SuratPengajuan $surat): bool
    {
        return $user->isAdmin() || $user->isAsessor();
    }

    public function review(User $user, SuratPengajuan $surat): bool
    {
        return $user->isAdmin() || $user->isAsessor();
    }

    public function assign(User $user): bool
    {
        return $user->isAdmin();
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasMany<SuratPengajuan>
     */
    public function suratPengajuan(): HasMany
    {
        return $this->hasMany(SuratPengajuan::class);
    }

    /**
     * @return BelongsToMany<SuratPengajuan>
     */
    public function pengajuanDinilai(): BelongsToMany
    {
        return $this->belongsToMany(SuratPengajuan::class, 'penilai_pengajuan', 'user_id', 'surat_pengajuan_id')
            ->withPivot(['ditugaskan_oleh', 'tanggal_penugasan'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<PenilaianEtik>
     */
    public function penilaianEtik(): HasMany
    {
        return $this->hasMany(PenilaianEtik::class, 'penilai_id');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isApplicant(): bool
    {
        return $this->hasRole('applicant');
    }

    public function isReviewer(): bool
    {
        return $this->hasRole('reviewer');
    }
}

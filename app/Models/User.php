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

    public const ROLE_ADMIN = 'admin';
    public const ROLE_KETUA_KEPK = 'ketua_kepk';
    public const ROLE_ANGGOTA_KEPK = 'anggota_kepk';
    public const ROLE_REVIEWER = 'reviewer';
    public const ROLE_APPLICANT = 'applicant';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_KETUA_KEPK,
        self::ROLE_ANGGOTA_KEPK,
        self::ROLE_REVIEWER,
        self::ROLE_APPLICANT,
    ];

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isKetuaKepk(): bool
    {
        return $this->hasRole('ketua_kepk');
    }

    public function isAnggotaKepk(): bool
    {
        return $this->hasRole('anggota_kepk');
    }

    public function isApplicant(): bool
    {
        return $this->hasAnyRole(['applicant', 'ketua_kepk', 'anggota_kepk']);
    }

    public function isReviewer(): bool
    {
        return $this->hasRole('reviewer');
    }
}

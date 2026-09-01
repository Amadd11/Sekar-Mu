<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratPengajuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surat_pengajuan';

    protected $fillable = [
        'user_id',
        'kepk_id',
        'nomor_berkas',
        'status',
        'diajukan_pada',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
        ];
    }

    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    // Legacy status aliases mapped for backward compatibility
    public const STATUS_DRAFT = 'in_progress';
    public const STATUS_SUBMITTED = 'in_progress';
    public const STATUS_UNDER_REVIEW = 'in_progress';
    public const STATUS_REVISION_REQUIRED = 'in_progress';
    public const STATUS_RESUBMITTED = 'in_progress';

    public const STATUSES = [
        self::STATUS_IN_PROGRESS,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    /**
     * @return list<string>
     */
    public static function statuses(): array
    {
        return self::STATUSES;
    }

    /**
     * @return BelongsTo<User, SuratPengajuan>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Kepk, SuratPengajuan>
     */
    public function kepk(): BelongsTo
    {
        return $this->belongsTo(Kepk::class, 'kepk_id');
    }

    /**
     * @return HasOne<FormulirAplikasi>
     */
    public function formulirAplikasi(): HasOne
    {
        return $this->hasOne(FormulirAplikasi::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasOne<ProfilKepk>
     */
    public function profilKepk(): HasOne
    {
        return $this->hasOne(ProfilKepk::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<AnggotaKepk>
     */
    public function anggotaKepk(): HasMany
    {
        return $this->hasMany(AnggotaKepk::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<JawabanEvaluasi>
     */
    public function jawabanEvaluasi(): HasMany
    {
        return $this->hasMany(JawabanEvaluasi::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<ListProtokol>
     */
    public function listProtokol(): HasMany
    {
        return $this->hasMany(ListProtokol::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<Dokumen>
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<PenilaiPengajuan>
     */
    public function penilaiPengajuan(): HasMany
    {
        return $this->hasMany(PenilaiPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * @return BelongsToMany<User>
     */
    public function penilai(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'penilai_pengajuan', 'surat_pengajuan_id', 'user_id')
            ->withPivot(['ditugaskan_oleh', 'tanggal_penugasan'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<PenilaianEtik>
     */
    public function penilaianEtik(): HasMany
    {
        return $this->hasMany(PenilaianEtik::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<PenilaianButirAsesor>
     */
    public function penilaianButirAsesor(): HasMany
    {
        return $this->hasMany(PenilaianButirAsesor::class, 'surat_pengajuan_id');
    }

    /**
     * @return HasMany<CorrectiveAction>
     */
    public function correctiveActions(): HasMany
    {
        return $this->hasMany(CorrectiveAction::class, 'surat_pengajuan_id');
    }

    public function isInProgress(): bool
    {
        return in_array($this->status, ['in_progress', 'draft', 'submitted', 'under_review', 'revision_required', 'resubmitted'], true);
    }

    public function isDraft(): bool
    {
        return $this->isInProgress();
    }

    public function isSubmitted(): bool
    {
        return $this->isInProgress();
    }

    public function isUnderReview(): bool
    {
        return $this->isInProgress();
    }

    public function isRevisionRequired(): bool
    {
        return false;
    }

    public function isResubmitted(): bool
    {
        return $this->isInProgress();
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isEditable(): bool
    {
        return $this->isInProgress();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabel($this->status);
    }

    public function getStatusIconAttribute(): string
    {
        return self::statusIcon($this->status);
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return self::statusBadgeClasses($this->status);
    }

    public function getFormattedIdAttribute(): string
    {
        return ! empty($this->nomor_berkas) ? $this->nomor_berkas : (string) $this->id;
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Terakreditasi',
            'rejected' => 'Tidak Lolos',
            default => 'Proses Evaluasi',
        };
    }

    public static function statusIcon(string $status): string
    {
        return match ($status) {
            'approved' => 'verified',
            'rejected' => 'cancel',
            default => 'sync',
        };
    }

    public static function statusBadgeClasses(string $status): string
    {
        return match ($status) {
            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/80',
            default => 'bg-blue-50 text-blue-700 border-blue-200/80',
        };
    }
}

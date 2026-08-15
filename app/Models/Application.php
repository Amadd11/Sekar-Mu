<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_REVISION_REQUIRED = 'revision_required';
    public const STATUS_RESUBMITTED = 'resubmitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SUBMITTED,
        self::STATUS_UNDER_REVIEW,
        self::STATUS_REVISION_REQUIRED,
        self::STATUS_RESUBMITTED,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'user_id',
        'kepk_id',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, Application>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Kepk, Application>
     */
    public function kepk(): BelongsTo
    {
        return $this->belongsTo(Kepk::class);
    }

    /**
     * @return HasOne<ApplicationInformation>
     */
    public function information(): HasOne
    {
        return $this->hasOne(ApplicationInformation::class);
    }

    /**
     * @return HasOne<ApplicationProfile>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(ApplicationProfile::class);
    }

    /**
     * @return HasMany<ApplicationMember>
     */
    public function members(): HasMany
    {
        return $this->hasMany(ApplicationMember::class);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isRevisionRequired(): bool
    {
        return $this->status === self::STATUS_REVISION_REQUIRED;
    }

    public function isResubmitted(): bool
    {
        return $this->status === self::STATUS_RESUBMITTED;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REVISION_REQUIRED], true);
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Diajukan (Submitted)',
            self::STATUS_UNDER_REVIEW => 'Sedang Ditelaah',
            self::STATUS_REVISION_REQUIRED => 'Perlu Revisi',
            self::STATUS_RESUBMITTED => 'Diajukan Ulang',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function statusBadgeClasses(string $status): string
    {
        return match ($status) {
            self::STATUS_DRAFT => 'bg-slate-100 text-slate-700 border-slate-200',
            self::STATUS_SUBMITTED => 'bg-blue-100 text-blue-700 border-blue-200',
            self::STATUS_UNDER_REVIEW => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            self::STATUS_REVISION_REQUIRED => 'bg-orange-100 text-orange-700 border-orange-200',
            self::STATUS_RESUBMITTED => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            self::STATUS_APPROVED => 'bg-green-100 text-green-700 border-green-200',
            self::STATUS_REJECTED => 'bg-red-100 text-red-700 border-red-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}

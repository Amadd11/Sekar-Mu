<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

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
     * @return list<string>
     */
    public static function statuses(): array
    {
        return [
            'draft',
            'submitted',
            'under_review',
            'revision_required',
            'resubmitted',
            'approved',
            'rejected',
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

    /**
     * @return HasMany<AssessmentAnswer>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    /**
     * @return HasMany<ResearchProtocol>
     */
    public function protocols(): HasMany
    {
        return $this->hasMany(ResearchProtocol::class);
    }

    /**
     * @return HasMany<Document>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @return HasMany<ApplicationReviewer>
     */
    public function applicationReviewers(): HasMany
    {
        return $this->hasMany(ApplicationReviewer::class);
    }

    /**
     * @return BelongsToMany<User>
     */
    public function assignedReviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'application_reviewers', 'application_id', 'user_id')
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isRevisionRequired(): bool
    {
        return $this->status === 'revision_required';
    }

    public function isResubmitted(): bool
    {
        return $this->status === 'resubmitted';
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
        return in_array($this->status, ['draft', 'revision_required'], true);
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Draft',
            'submitted' => 'Diajukan (Submitted)',
            'under_review' => 'Sedang Ditelaah (Under Review)',
            'revision_required' => 'Perlu Revisi (Revision Required)',
            'resubmitted' => 'Diajukan Ulang (Resubmitted)',
            'approved' => 'Disetujui (Approved)',
            'rejected' => 'Ditolak (Rejected)',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function statusBadgeClasses(string $status): string
    {
        return match ($status) {
            'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
            'submitted' => 'bg-blue-100 text-blue-700 border-blue-200',
            'under_review' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'revision_required' => 'bg-orange-100 text-orange-700 border-orange-200',
            'resubmitted' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'approved' => 'bg-green-100 text-green-700 border-green-200',
            'rejected' => 'bg-red-100 text-red-700 border-red-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}

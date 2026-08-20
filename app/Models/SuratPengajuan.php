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
        'status',
        'diajukan_pada',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
        ];
    }

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
            'submitted' => 'Diajukan',
            'under_review' => 'Sedang Dinilai',
            'revision_required' => 'Perlu Perbaikan',
            'resubmitted' => 'Diajukan Ulang',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function statusIcon(string $status): string
    {
        return match ($status) {
            'draft' => 'edit_document',
            'submitted' => 'send',
            'under_review' => 'pending',
            'revision_required' => 'warning',
            'resubmitted' => 'restart_alt',
            'approved' => 'verified',
            'rejected' => 'cancel',
            default => 'info',
        };
    }

    public static function statusBadgeClasses(string $status): string
    {
        return match ($status) {
            'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
            'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
            'under_review' => 'bg-amber-100 text-amber-800 border-amber-200',
            'revision_required' => 'bg-orange-100 text-orange-800 border-orange-200',
            'resubmitted' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}

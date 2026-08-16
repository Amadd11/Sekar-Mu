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
            'under_review' => 'Sedang Dinilai (Under Review)',
            'revision_required' => 'Perlu Perbaikan (Revision Required)',
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
            'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'reviewer_id',
        'recommendation',
        'notes',
        'decision_date',
    ];

    protected function casts(): array
    {
        return [
            'decision_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Application, Review>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * @return BelongsTo<User, Review>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * @return HasMany<ReviewComment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ReviewComment::class);
    }

    public static function recommendationLabel(string $recommendation): string
    {
        return match ($recommendation) {
            'approved' => 'Disetujui (Layak Etik)',
            'revision_required' => 'Perlu Perbaikan / Revisi',
            'rejected' => 'Ditolak (Tidak Layak Etik)',
            default => ucfirst($recommendation),
        };
    }

    public static function recommendationBadgeClasses(string $recommendation): string
    {
        return match ($recommendation) {
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'revision_required' => 'bg-amber-100 text-amber-800 border-amber-200',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}

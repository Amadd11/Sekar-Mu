<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ButirEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'butir_evaluasi';

    protected $fillable = [
        'kelompok_evaluasi_id',
        'kode',
        'pertanyaan',
        'evidence_required',
        'parent_item_id',
        'standar',
        'parameter',
    ];

    protected function casts(): array
    {
        return [];
    }

    /**
     * @return BelongsTo<KelompokEvaluasi, ButirEvaluasi>
     */
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(KelompokEvaluasi::class, 'kelompok_evaluasi_id');
    }

    /**
     * @return BelongsTo<ButirEvaluasi, ButirEvaluasi>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ButirEvaluasi::class, 'parent_item_id');
    }

    /**
     * @return HasMany<ButirEvaluasi>
     */
    public function children(): HasMany
    {
        return $this->hasMany(ButirEvaluasi::class, 'parent_item_id');
    }

    /**
     * @return HasMany<JawabanEvaluasi>
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(JawabanEvaluasi::class, 'butir_evaluasi_id');
    }

    /**
     * @return HasMany<PenilaianButirAsesor>
     */
    public function penilaianAsesor(): HasMany
    {
        return $this->hasMany(PenilaianButirAsesor::class, 'butir_evaluasi_id');
    }

    /**
     * @return HasMany<CorrectiveAction>
     */
    public function correctiveActions(): HasMany
    {
        return $this->hasMany(CorrectiveAction::class, 'butir_evaluasi_id');
    }
}

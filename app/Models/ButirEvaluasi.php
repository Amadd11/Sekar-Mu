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
        'pertanyaan',
        'urutan',
    ];

    /**
     * @return BelongsTo<KelompokEvaluasi, ButirEvaluasi>
     */
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(KelompokEvaluasi::class, 'kelompok_evaluasi_id');
    }

    /**
     * @return HasMany<JawabanEvaluasi>
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(JawabanEvaluasi::class, 'butir_evaluasi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelompokEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'kelompok_evaluasi';

    protected $fillable = [
        'bagian_evaluasi_id',
        'nama',
        'urutan',
    ];

    /**
     * @return BelongsTo<BagianEvaluasi, KelompokEvaluasi>
     */
    public function bagian(): BelongsTo
    {
        return $this->belongsTo(BagianEvaluasi::class, 'bagian_evaluasi_id');
    }

    /**
     * @return HasMany<ButirEvaluasi>
     */
    public function butir(): HasMany
    {
        return $this->hasMany(ButirEvaluasi::class, 'kelompok_evaluasi_id')->orderBy('urutan');
    }
}

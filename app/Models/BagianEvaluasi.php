<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BagianEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'bagian_evaluasi';

    protected $fillable = [
        'kode',
        'nama',
        'urutan',
    ];

    /**
     * @return HasMany<KelompokEvaluasi>
     */
    public function kelompok(): HasMany
    {
        return $this->hasMany(KelompokEvaluasi::class, 'bagian_evaluasi_id')->orderBy('urutan');
    }

    /**
     * @return HasManyThrough<ButirEvaluasi, KelompokEvaluasi>
     */
    public function butir(): HasManyThrough
    {
        return $this->hasManyThrough(ButirEvaluasi::class, KelompokEvaluasi::class, 'bagian_evaluasi_id', 'kelompok_evaluasi_id');
    }
}

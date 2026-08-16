<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kepk extends Model
{
    use HasFactory;

    protected $table = 'kepk';

    protected $fillable = [
        'institusi_id',
        'code',
        'name',
        'status',
    ];

    /**
     * @return BelongsTo<Institusi, Kepk>
     */
    public function institusi(): BelongsTo
    {
        return $this->belongsTo(Institusi::class, 'institusi_id');
    }

    /**
     * @return HasMany<SuratPengajuan>
     */
    public function suratPengajuan(): HasMany
    {
        return $this->hasMany(SuratPengajuan::class, 'kepk_id');
    }
}

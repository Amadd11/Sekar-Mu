<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanPenilaian extends Model
{
    use HasFactory;

    protected $table = 'catatan_penilaian';

    protected $fillable = [
        'penilaian_etik_id',
        'user_id',
        'catatan',
        'selesai',
    ];

    protected function casts(): array
    {
        return [
            'selesai' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<PenilaianEtik, CatatanPenilaian>
     */
    public function penilaianEtik(): BelongsTo
    {
        return $this->belongsTo(PenilaianEtik::class, 'penilaian_etik_id');
    }

    /**
     * @return BelongsTo<User, CatatanPenilaian>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

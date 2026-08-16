<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaiPengajuan extends Model
{
    use HasFactory;

    protected $table = 'penilai_pengajuan';

    protected $fillable = [
        'surat_pengajuan_id',
        'user_id',
        'ditugaskan_oleh',
        'tanggal_penugasan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penugasan' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<SuratPengajuan, PenilaiPengajuan>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * @return BelongsTo<User, PenilaiPengajuan>
     */
    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<User, PenilaiPengajuan>
     */
    public function penugasa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditugaskan_oleh');
    }
}

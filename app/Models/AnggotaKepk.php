<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKepk extends Model
{
    use HasFactory;

    protected $table = 'anggota_kepk';

    protected $fillable = [
        'surat_pengajuan_id',
        'nama',
        'jabatan',
        'email',
        'telepon',
    ];

    /**
     * @return BelongsTo<SuratPengajuan, AnggotaKepk>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }
}

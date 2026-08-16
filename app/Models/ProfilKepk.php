<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilKepk extends Model
{
    use HasFactory;

    protected $table = 'profil_kepk';

    protected $fillable = [
        'surat_pengajuan_id',
        'deskripsi',
        'visi',
        'misi',
    ];

    /**
     * @return BelongsTo<SuratPengajuan, ProfilKepk>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }
}

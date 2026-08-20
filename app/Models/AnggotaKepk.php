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
        'peran_etik',
        'keahlian',
        'afiliasi',
        'gender',
        'pendidikan',
        'status_aktif',
        'email',
        'telepon',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<SuratPengajuan, AnggotaKepk>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }
}

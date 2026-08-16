<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormulirAplikasi extends Model
{
    use HasFactory;

    protected $table = 'formulir_aplikasi';

    protected $fillable = [
        'surat_pengajuan_id',
        'nama_institusi',
        'singkatan',
        'alamat',
        'kota',
        'telepon',
        'email',
    ];

    /**
     * @return BelongsTo<SuratPengajuan, FormulirAplikasi>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }
}

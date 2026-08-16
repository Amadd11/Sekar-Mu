<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'jawaban_evaluasi';

    protected $fillable = [
        'surat_pengajuan_id',
        'butir_evaluasi_id',
        'skor',
        'catatan',
        'bukti',
    ];

    /**
     * @return BelongsTo<SuratPengajuan, JawabanEvaluasi>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * @return BelongsTo<ButirEvaluasi, JawabanEvaluasi>
     */
    public function butir(): BelongsTo
    {
        return $this->belongsTo(ButirEvaluasi::class, 'butir_evaluasi_id');
    }
}

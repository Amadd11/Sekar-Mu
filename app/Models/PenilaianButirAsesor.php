<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianButirAsesor extends Model
{
    use HasFactory;

    protected $table = 'penilaian_butir_asesor';

    public const STRENGTH_E0 = 'E0';
    public const STRENGTH_E1 = 'E1';
    public const STRENGTH_E2 = 'E2';
    public const STRENGTH_E3 = 'E3';
    public const STRENGTH_E4 = 'E4';

    public const STRENGTHS = [
        self::STRENGTH_E0,
        self::STRENGTH_E1,
        self::STRENGTH_E2,
        self::STRENGTH_E3,
        self::STRENGTH_E4,
    ];

    protected $fillable = [
        'surat_pengajuan_id',
        'penilai_id',
        'butir_evaluasi_id',
        'skor',
        'evidence_strength',
        'catatan',
        'temuan',
        'rekomendasi',
    ];

    /**
     * @return BelongsTo<SuratPengajuan, PenilaianButirAsesor>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * @return BelongsTo<User, PenilaianButirAsesor>
     */
    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    /**
     * @return BelongsTo<ButirEvaluasi, PenilaianButirAsesor>
     */
    public function butir(): BelongsTo
    {
        return $this->belongsTo(ButirEvaluasi::class, 'butir_evaluasi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class JawabanEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'jawaban_evaluasi';

    public const ASSESSMENT_A = 'A';
    public const ASSESSMENT_B = 'B';
    public const ASSESSMENT_C = 'C';
    public const ASSESSMENT_D = 'D';

    public const ASSESSMENTS = [
        self::ASSESSMENT_A,
        self::ASSESSMENT_B,
        self::ASSESSMENT_C,
        self::ASSESSMENT_D,
    ];

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
        'butir_evaluasi_id',
        'skor',
        'catatan',
        'bukti',
        'file_path',
        'file_name',
        'file_size',
        'evidence_strength',
        'pic_user_id',
    ];

    /**
     * Get file URL from storage.
     */
    public function fileUrl(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Format human-readable file size.
     */
    public function formatUkuran(): string
    {
        if (! $this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($this->file_size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

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

    /**
     * @return BelongsTo<User, JawabanEvaluasi>
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }
}

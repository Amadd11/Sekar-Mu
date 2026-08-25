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
        'file_attachments',
        'evidence_strength',
        'pic_user_id',
    ];

    protected function casts(): array
    {
        return [
            'file_attachments' => 'array',
        ];
    }

    /**
     * Get all attached files as array of objects:
     * [['name' => '...', 'path' => '...', 'size' => 12345], ...]
     *
     * @return list<array{name: string, path: string, size: int}>
     */
    public function getAttachments(): array
    {
        if (! empty($this->file_attachments) && is_array($this->file_attachments)) {
            return array_values($this->file_attachments);
        }

        return [];
    }

    public function hasAttachments(): bool
    {
        return count($this->getAttachments()) > 0;
    }

    public function totalAttachmentCount(): int
    {
        return count($this->getAttachments());
    }

    /**
     * Get primary file URL from storage.
     */
    public function fileUrl(): ?string
    {
        $attachments = $this->getAttachments();
        if (! empty($attachments)) {
            return Storage::url($attachments[0]['path']);
        }

        return null;
    }

    /**
     * Format human-readable file size for all attached files.
     */
    public function formatUkuran(): string
    {
        $attachments = $this->getAttachments();
        if (! empty($attachments)) {
            $total = array_sum(array_column($attachments, 'size'));
            return self::formatBytes((int) $total);
        }

        return '0 B';
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes, 1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 1) . ' ' . $units[$pow];
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

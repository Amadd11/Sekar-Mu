<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ListProtokol extends Model
{
    use HasFactory;

    protected $table = 'list_protokol';

    public const REVIEW_EXEMPTED = 'exempted';

    public const REVIEW_EXPEDITED = 'expedited';

    public const REVIEW_FULL_BOARD = 'full_board';

    public const REVIEW_TYPES = [
        self::REVIEW_EXEMPTED,
        self::REVIEW_EXPEDITED,
        self::REVIEW_FULL_BOARD,
    ];

    protected $fillable = [
        'surat_pengajuan_id',
        'nomor_protokol',
        'judul',
        'peneliti_utama',
        'review_type',
        'institusi_asal',
        'tanggal_pengajuan',
        'tanggal_review',
        'nomor_surat_etik',
        'status_etik',
        'status',
        'dokumen_path',
        'dokumen_nama',
        'dokumen_ukuran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_review' => 'date',
            'dokumen_ukuran' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<SuratPengajuan, ListProtokol>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    public function hasDokumen(): bool
    {
        return ! empty($this->dokumen_path);
    }

    public function getDokumenUrlAttribute(): ?string
    {
        return $this->dokumen_path ? Storage::url($this->dokumen_path) : null;
    }

    public function formatUkuranDokumen(): string
    {
        $bytes = $this->dokumen_ukuran ?: 0;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}

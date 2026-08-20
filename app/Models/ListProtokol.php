<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_review' => 'date',
        ];
    }

    /**
     * @return BelongsTo<SuratPengajuan, ListProtokol>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }
}

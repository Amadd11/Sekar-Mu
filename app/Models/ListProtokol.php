<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListProtokol extends Model
{
    use HasFactory;

    protected $table = 'list_protokol';

    protected $fillable = [
        'surat_pengajuan_id',
        'nomor_protokol',
        'judul',
        'peneliti_utama',
        'tanggal_pengajuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
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

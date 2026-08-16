<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenilaianEtik extends Model
{
    use HasFactory;

    protected $table = 'penilaian_etik';

    protected $fillable = [
        'surat_pengajuan_id',
        'penilai_id',
        'rekomendasi',
        'catatan',
        'tanggal_keputusan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_keputusan' => 'date',
        ];
    }

    /**
     * @return BelongsTo<SuratPengajuan, PenilaianEtik>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * @return BelongsTo<User, PenilaianEtik>
     */
    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    /**
     * @return HasMany<CatatanPenilaian>
     */
    public function catatanPenilaian(): HasMany
    {
        return $this->hasMany(CatatanPenilaian::class, 'penilaian_etik_id');
    }

    public static function labelRekomendasi(string $rekomendasi): string
    {
        return match ($rekomendasi) {
            'approved' => 'Disetujui (Layak Etik)',
            'revision_required' => 'Perlu Perbaikan / Revisi',
            'rejected' => 'Ditolak (Tidak Layak Etik)',
            default => ucfirst($rekomendasi),
        };
    }

    public static function badgeRekomendasi(string $rekomendasi): string
    {
        return match ($rekomendasi) {
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'revision_required' => 'bg-amber-100 text-amber-800 border-amber-200',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrectiveAction extends Model
{
    use HasFactory;

    protected $table = 'corrective_actions';

    public const STATUS_OPEN = 'OPEN';
    public const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    public const STATUS_SUBMITTED = 'SUBMITTED';
    public const STATUS_VERIFIED = 'VERIFIED';
    public const STATUS_CLOSED = 'CLOSED';

    public const PRIORITY_HIGH = 'HIGH';
    public const PRIORITY_MEDIUM = 'MEDIUM';
    public const PRIORITY_LOW = 'LOW';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_IN_PROGRESS,
        self::STATUS_SUBMITTED,
        self::STATUS_VERIFIED,
        self::STATUS_CLOSED,
    ];

    public const PRIORITIES = [
        self::PRIORITY_HIGH,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_LOW,
    ];

    protected $fillable = [
        'surat_pengajuan_id',
        'butir_evaluasi_id',
        'finding',
        'risk',
        'action',
        'pic_name',
        'priority',
        'deadline',
        'status',
        'evidence_path',
        'verification_notes',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    /**
     * @return BelongsTo<SuratPengajuan, CorrectiveAction>
     */
    public function suratPengajuan(): BelongsTo
    {
        return $this->belongsTo(SuratPengajuan::class, 'surat_pengajuan_id');
    }

    /**
     * @return BelongsTo<ButirEvaluasi, CorrectiveAction>
     */
    public function butir(): BelongsTo
    {
        return $this->belongsTo(ButirEvaluasi::class, 'butir_evaluasi_id');
    }
}

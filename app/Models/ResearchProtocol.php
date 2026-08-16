<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchProtocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'protocol_number',
        'title',
        'principal_investigator',
        'submission_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Application, ResearchProtocol>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_group_id',
        'question',
        'order',
    ];

    /**
     * @return BelongsTo<AssessmentGroup, AssessmentItem>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AssessmentGroup::class, 'assessment_group_id');
    }

    /**
     * @return HasMany<AssessmentAnswer>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }
}

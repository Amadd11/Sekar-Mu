<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_section_id',
        'name',
        'order',
    ];

    /**
     * @return BelongsTo<AssessmentSection, AssessmentGroup>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(AssessmentSection::class, 'assessment_section_id');
    }

    /**
     * @return HasMany<AssessmentItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(AssessmentItem::class)->orderBy('order');
    }
}

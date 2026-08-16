<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AssessmentSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'order',
    ];

    /**
     * @return HasMany<AssessmentGroup>
     */
    public function groups(): HasMany
    {
        return $this->hasMany(AssessmentGroup::class)->orderBy('order');
    }

    /**
     * @return HasManyThrough<AssessmentItem, AssessmentGroup>
     */
    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(AssessmentItem::class, AssessmentGroup::class);
    }
}

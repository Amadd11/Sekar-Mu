<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institusi extends Model
{
    use HasFactory;

    protected $table = 'institusi';

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'email',
    ];

    /**
     * @return HasMany<Kepk>
     */
    public function kepks(): HasMany
    {
        return $this->hasMany(Kepk::class, 'institusi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'position',
        'email',
        'phone',
    ];

    /**
     * @return BelongsTo<Application, ApplicationMember>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}

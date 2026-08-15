<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'description',
        'vision',
        'mission',
    ];

    /**
     * @return BelongsTo<Application, ApplicationProfile>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationInformation extends Model
{
    use HasFactory;

    protected $table = 'application_informations';

    protected $fillable = [
        'application_id',
        'name',
        'abbreviation',
        'address',
        'city',
        'phone',
        'email',
    ];

    /**
     * @return BelongsTo<Application, ApplicationInformation>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}

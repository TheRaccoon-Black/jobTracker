<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationLog extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'application_id', 'user_id', 'event', 'old_value', 'new_value', 'note',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}

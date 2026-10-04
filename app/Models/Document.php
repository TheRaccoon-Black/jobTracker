<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'user_id', 'file_name', 'file_path', 'type', 'application_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public const TYPES = ['CV', 'Portfolio', 'Cover Letter', 'Other'];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id', 'interview_stage', 'interviewer',
        'schedule_date', 'link_meeting', 'notes',
    ];

    protected $casts = [
        'schedule_date' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public const STAGES = [
        'Screening', 'HR Interview', 'Technical Test',
        'User Interview', 'Final/C-Level Interview',
    ];
}

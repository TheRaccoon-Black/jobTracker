<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'company', 'title', 'status',
        'job_posting_link', 'contact', 'application_date',
        'salary_range', 'notes', 'next_follow_up', 'follow_up_notes', 'tags',
    ];

    protected $casts = [
        'application_date' => 'date',
        'next_follow_up' => 'date',
        'tags' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApplicationLog::class)->latest()->limit(20);
    }

    public function latestLog(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ApplicationLog::class)->latestOfMany('id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'application_id');
    }

    public const STATUSES = [
        'Wishlist', 'Applied', 'Assessment', 'HR Interview',
        'User Interview', 'Offered', 'Rejected', 'Ghosted',
    ];

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Wishlist', 'Applied', 'Assessment', 'HR Interview', 'User Interview']);
    }

    /** Filter applications carrying at least one of the given tags (JSON LIKE match). */
    public function scopeTagged($query, array $tags)
    {
        foreach ($tags as $tag) {
            $query->whereJsonContains('tags', $tag);
        }
        return $query;
    }

    /**
     * Stale: active applications that stopped moving.
     * Early-stage (Wishlist/Applied/Assessment) idle > 14 days, or
     * interview-track (HR/User Interview) idle > 7 days.
     * Returns the query filtered in PHP so the date math stays readable.
     */
    /** Write an audit entry for this application. */
    public function log(string $event, ?string $oldValue = null, ?string $newValue = null, ?string $note = null, ?int $userId = null): void
    {
        $this->logs()->create([
            'user_id' => $userId ?? $this->user_id,
            'event' => $event,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'note' => $note,
        ]);
    }

    public function scopeStale($query)
    {
        $early = ['Wishlist', 'Applied', 'Assessment'];
        $interview = ['HR Interview', 'User Interview'];

        return $query->active()->get()->filter(function ($app) use ($early, $interview) {
            $staleDays = in_array($app->status, $interview) ? 7 : 14;
            $stale = $app->application_date && $app->application_date->isBefore(now()->subDays($staleDays));
            return $stale;
        });
    }
}

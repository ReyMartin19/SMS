<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'audience',
        'is_pinned',
        'published_at',
        'expiry_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    /**
     * Get the author that created the announcement.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include announcements visible to a specific role.
     * Non-admin roles only see targeted or 'all' announcements that are published and not expired.
     */
    public function scopeVisible($query, string $role)
    {
        // Normalizing role: Superadmin and Admin can see everything in feed or targeted roles
        if (in_array($role, ['superadmin', 'admin'])) {
            return $query->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', today());
            });
        }

        // Other roles only see 'all' or their specific role target
        return $query->where(function ($q) use ($role) {
                $q->where('audience', 'all')
                  ->orWhere('audience', $role);
            })
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', today());
            });
    }

    /**
     * Scope a query to only include pinned announcements.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}

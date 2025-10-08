<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialShare extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'page_title',
        'social_platform_id',
        'user_ip',
        'user_agent',
        'referrer',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the platform that this share belongs to.
     */
    public function platform()
    {
        return $this->belongsTo(SocialPlatform::class, 'social_platform_id');
    }

    /**
     * Scope a query to filter by platform.
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('social_platform_id', $platformId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get recent shares.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}

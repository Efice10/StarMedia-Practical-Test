<?php

namespace App\Services\Analytics;

use App\Models\SocialShare;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get shares grouped by platform
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getSharesByPlatform($startDate = null, $endDate = null)
    {
        $query = SocialShare::query()
            ->select('social_platform_id', DB::raw('count(*) as count'))
            ->with('platform:id,name,display_name,color')
            ->groupBy('social_platform_id');

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        $shares = $query->get();

        return $shares->map(function ($share) {
            return [
                'platform_id' => $share->social_platform_id,
                'platform_name' => $share->platform->name,
                'display_name' => $share->platform->display_name,
                'color' => $share->platform->color,
                'count' => $share->count,
            ];
        })->toArray();
    }

    /**
     * Get shares grouped by date
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $platformId
     * @return array
     */
    public function getSharesByDate($startDate = null, $endDate = null, $platformId = null)
    {
        $query = SocialShare::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc');

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        if ($platformId) {
            $query->byPlatform($platformId);
        }

        return $query->get()->toArray();
    }

    /**
     * Get top shared URLs
     *
     * @param int $limit
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getTopSharedUrls($limit = 10, $startDate = null, $endDate = null)
    {
        $query = SocialShare::query()
            ->select('url', 'page_title', DB::raw('count(*) as share_count'))
            ->groupBy('url', 'page_title')
            ->orderBy('share_count', 'desc')
            ->limit($limit);

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        return $query->get()->toArray();
    }

    /**
     * Get dashboard statistics
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getDashboardStats($startDate = null, $endDate = null)
    {
        $query = SocialShare::query();

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        $totalShares = $query->count();
        $uniqueUrls = SocialShare::query()
            ->when($startDate && $endDate, fn($q) => $q->dateRange($startDate, $endDate))
            ->distinct('url')
            ->count('url');

        $mostPopularPlatform = SocialShare::query()
            ->select('social_platform_id', DB::raw('count(*) as count'))
            ->with('platform:id,display_name')
            ->when($startDate && $endDate, fn($q) => $q->dateRange($startDate, $endDate))
            ->groupBy('social_platform_id')
            ->orderBy('count', 'desc')
            ->first();

        return [
            'total_shares' => $totalShares,
            'unique_urls' => $uniqueUrls,
            'most_popular_platform' => $mostPopularPlatform ? [
                'name' => $mostPopularPlatform->platform->display_name,
                'count' => $mostPopularPlatform->count,
            ] : null,
        ];
    }
}

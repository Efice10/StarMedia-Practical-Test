<?php

namespace App\Actions\Analytics;

use App\Services\Analytics\AnalyticsService;

class GetDashboardData
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Execute the get dashboard data action
     *
     * @param array $filters
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(array $filters = [])
    {
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;

        $stats = $this->analyticsService->getDashboardStats($startDate, $endDate);
        $sharesByPlatform = $this->analyticsService->getSharesByPlatform($startDate, $endDate);
        $sharesByDate = $this->analyticsService->getSharesByDate($startDate, $endDate);
        $topUrls = $this->analyticsService->getTopSharedUrls(10, $startDate, $endDate);

        return success('Dashboard data retrieved successfully', [
            'stats' => $stats,
            'shares_by_platform' => $sharesByPlatform,
            'shares_by_date' => $sharesByDate,
            'top_urls' => $topUrls,
            'filters_applied' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }
}

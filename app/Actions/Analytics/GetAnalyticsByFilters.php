<?php

namespace App\Actions\Analytics;

use App\Services\Analytics\AnalyticsService;

class GetAnalyticsByFilters
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Execute the get analytics by filters action
     *
     * @param array $filters
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(array $filters)
    {
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $platformId = $filters['platform_id'] ?? null;

        $data = [];

        if (isset($filters['group_by'])) {
            switch ($filters['group_by']) {
                case 'platform':
                    $data = $this->analyticsService->getSharesByPlatform($startDate, $endDate);
                    break;
                case 'date':
                    $data = $this->analyticsService->getSharesByDate($startDate, $endDate, $platformId);
                    break;
            }
        } else {
            $data = [
                'by_platform' => $this->analyticsService->getSharesByPlatform($startDate, $endDate),
                'by_date' => $this->analyticsService->getSharesByDate($startDate, $endDate, $platformId),
            ];
        }

        return success('Analytics data retrieved successfully', [
            'data' => $data,
            'filters_applied' => $filters,
        ]);
    }
}

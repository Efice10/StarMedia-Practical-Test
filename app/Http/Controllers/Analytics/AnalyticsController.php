<?php

namespace App\Http\Controllers\Analytics;

use App\Actions\Analytics\GetAnalyticsByFilters;
use App\Actions\Analytics\GetDashboardData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\GetAnalyticsRequest;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Get dashboard analytics data
     */
    public function dashboard(Request $request, GetDashboardData $action)
    {
        return $action->execute($request->all());
    }

    /**
     * Get analytics data with filters
     */
    public function index(GetAnalyticsRequest $request, GetAnalyticsByFilters $action)
    {
        return $action->execute($request->validated());
    }
}

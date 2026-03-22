<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmOpportunity;
use App\Models\CrmEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrmDashboardController extends Controller
{
    public function index()
    {
        // KPI: Total Pipeline Value
        $totalPipeline = CrmOpportunity::sum('estimated_value');

        // KPI: Conversion Rate (Won / Total Closed)
        $wonCount = CrmOpportunity::where('stage_id', 'won')->count();
        $lostCount = CrmOpportunity::where('stage_id', 'lost')->count();
        $totalClosed = $wonCount + $lostCount;
        $conversionRate = $totalClosed > 0 ? ($wonCount / $totalClosed) * 100 : 0;

        // KPI: Architect Ranking (ROI)
        $architectRanking = CrmOpportunity::select('architect_id', DB::raw('SUM(estimated_value) as total_generated'))
            ->whereNotNull('architect_id')
            ->with('architect')
            ->groupBy('architect_id')
            ->orderByDesc('total_generated')
            ->limit(5)
            ->get();

        return view('crm.dashboard.index', compact('totalPipeline', 'conversionRate', 'architectRanking'));
    }
}

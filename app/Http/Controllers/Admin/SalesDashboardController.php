<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Representative;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesDashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $representativeId = $request->input('representative_id');
        $division = $request->input('sales_division');
        $status = $request->input('order_status');

        $salesQuery = Sale::query()
            ->when($start && $end, fn($q) => $q->whereBetween('issue_date', [$start, $end]))
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->when($status, fn($q) => $q->where('order_status', $status));

        $sales = $salesQuery->get();

        $totalOrders = $sales->count();
        $totalRevenue = (float) $sales->sum('grand_total');
        $avgTicket = $totalOrders ? round($sales->avg('grand_total'), 2) : 0;

        $statusDist = Sale::select('order_status', DB::raw('COUNT(*) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween('issue_date', [$start, $end]))
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->groupBy('order_status')
            ->pluck('total', 'order_status')
            ->toArray();

        $divisionDist = Sale::select('sales_division', DB::raw('COUNT(*) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween('issue_date', [$start, $end]))
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->groupBy('sales_division')
            ->pluck('total', 'sales_division')
            ->toArray();

        $topRepresentatives = Sale::select('representative_id', DB::raw('SUM(grand_total) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween('issue_date', [$start, $end]))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->groupBy('representative_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $rep = Representative::find($row->representative_id);
                return [
                    'name' => $rep ? $rep->name : ('ID ' . $row->representative_id),
                    'total' => round((float)$row->total, 2),
                ];
            })
            ->toArray();

        $topProducts = SaleItem::select('product_id', DB::raw('SUM(subtotal) as total'), DB::raw('SUM(quantity) as qty'))
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereHas('sale', function ($qq) use ($start, $end) {
                    $qq->whereBetween('issue_date', [$start, $end]);
                });
            })
            ->when($representativeId, function ($q) use ($representativeId) {
                $q->whereHas('sale', function ($qq) use ($representativeId) {
                    $qq->where('representative_id', $representativeId);
                });
            })
            ->when($division, function ($q) use ($division) {
                $q->whereHas('sale', function ($qq) use ($division) {
                    $qq->where('sales_division', $division);
                });
            })
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $p = Product::find($row->product_id);
                return [
                    'name' => $p ? $p->name : ('ID ' . $row->product_id),
                    'total' => round((float)$row->total, 2),
                    'qty' => (float)$row->qty,
                ];
            })
            ->toArray();

        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $key = $m->format('M/Y');
            $monthlyRevenue[$key] = (float) Sale::when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
                ->when($division, fn($q) => $q->where('sales_division', $division))
                ->whereYear('issue_date', $m->year)
                ->whereMonth('issue_date', $m->month)
                ->sum('grand_total');
        }

        $completed = (int) ($statusDist['Completed'] ?? 0);
        $cancelled = (int) ($statusDist['Cancelled'] ?? 0);
        $effectiveBase = max(1, array_sum($statusDist) - $cancelled);
        $conversionRate = round(($completed / $effectiveBase) * 100, 2);

        $lateDeliveries = Sale::when($start && $end, fn($q) => $q->whereBetween('expected_delivery_date', [$start, $end]))
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->whereDate('expected_delivery_date', '<', Carbon::today())
            ->whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->count();

        $currentMonth = Carbon::now()->format('Y-m');
        $daysPassed = (int) Carbon::now()->day;
        $daysInMonth = (int) Carbon::now()->daysInMonth;
        $revenueToDate = (float) Sale::whereBetween('issue_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->sum('grand_total');
        $dailyAvg = $daysPassed ? ($revenueToDate / $daysPassed) : 0;
        $projectionRevenue = round($dailyAvg * $daysInMonth, 2);

        $recentOrders = Sale::when($start && $end, fn($q) => $q->whereBetween('issue_date', [$start, $end]))
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->latest('issue_date')
            ->take(10)
            ->get();

        $representatives = Representative::orderBy('name')->get();

        return view('admin.sales.dashboard', [
            'filters' => compact('start', 'end', 'representativeId', 'division', 'status'),
            'cards' => [
                'totalOrders' => $totalOrders,
                'totalRevenue' => round($totalRevenue, 2),
                'avgTicket' => $avgTicket,
                'conversionRate' => $conversionRate,
                'lateDeliveries' => $lateDeliveries,
                'projectionRevenue' => $projectionRevenue,
            ],
            'statusDist' => $statusDist,
            'divisionDist' => $divisionDist,
            'monthlyRevenue' => $monthlyRevenue,
            'topRepresentatives' => $topRepresentatives,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'representatives' => $representatives,
        ]);
    }
}


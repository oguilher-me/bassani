<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\AssemblySchedule;
use App\Models\AssemblyScheduleEvaluation;
use App\Models\Vehicle;
use App\Models\VehicleUsage;
use App\Models\Maintenance;
use App\Models\FuelUp;
use App\Models\VehicleFine;
use App\Models\PlannedShipment;
use App\Models\Representative;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralDashboardController extends Controller
{
    private function resolvePeriod(?string $period, ?string $start, ?string $end): array
    {
        if ($start && $end) {
            $currStart = Carbon::parse($start)->startOfDay();
            $currEnd = Carbon::parse($end)->endOfDay();
            $diffDays = $currStart->diffInDays($currEnd) + 1;
            $prevEnd = (clone $currStart)->subDay();
            $prevStart = (clone $prevEnd)->subDays(max(1, $diffDays - 1));
            return [$currStart, $currEnd, $prevStart, $prevEnd];
        }

        $p = $period ?: 'month';
        switch ($p) {
            case 'day':
                $currStart = Carbon::today();
                $currEnd = Carbon::today()->endOfDay();
                $prevStart = Carbon::yesterday();
                $prevEnd = Carbon::yesterday()->endOfDay();
                break;
            case 'week':
                $currStart = Carbon::now()->startOfWeek();
                $currEnd = Carbon::now()->endOfWeek();
                $prevStart = (clone $currStart)->subWeek();
                $prevEnd = (clone $currEnd)->subWeek();
                break;
            case 'year':
                $currStart = Carbon::now()->startOfYear();
                $currEnd = Carbon::now()->endOfYear();
                $prevStart = (clone $currStart)->subYear();
                $prevEnd = (clone $currEnd)->subYear();
                break;
            case 'month':
            default:
                $currStart = Carbon::now()->startOfMonth();
                $currEnd = Carbon::now()->endOfMonth();
                $prevStart = (clone $currStart)->subMonth();
                $prevEnd = (clone $currEnd)->subMonth();
        }
        return [$currStart, $currEnd, $prevStart, $prevEnd];
    }

    private function variation($current, $previous): float
    {
        if (!$previous) return $current ? 100.0 : 0.0;
        return round((($current - $previous) / $previous) * 100, 2);
    }

    public function index(Request $request)
    {
        $period = $request->input('period');
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $representativeId = $request->input('representative_id');
        $division = $request->input('sales_division');
        $regionState = $request->input('region_state');
        $regionCity = $request->input('region_city');

        [$currStart, $currEnd, $prevStart, $prevEnd] = $this->resolvePeriod($period, $start, $end);

        $salesFilter = Sale::query()
            ->whereBetween('issue_date', [$currStart, $currEnd])
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->when($regionState || $regionCity, function($q) use ($regionState, $regionCity) {
                $q->whereHas('customer', function($qq) use ($regionState, $regionCity) {
                    if ($regionState) $qq->where('address_state', $regionState);
                    if ($regionCity) $qq->where('address_city', $regionCity);
                });
            });

        $salesPrevFilter = Sale::query()
            ->whereBetween('issue_date', [$prevStart, $prevEnd])
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division));

        $salesTotalCurrent = (float) $salesFilter->clone()->sum('grand_total');
        $salesTotalPrevious = (float) $salesPrevFilter->clone()->sum('grand_total');

        $openOrdersCurrent = (int) $salesFilter->clone()->where('order_status', 'Open')->count();
        $openOrdersPrevious = (int) $salesPrevFilter->clone()->where('order_status', 'Open')->count();

        $delayedOrdersCurrent = (int) Sale::whereBetween('expected_delivery_date', [$currStart, $currEnd])
            ->when($representativeId, fn($q) => $q->where('representative_id', $representativeId))
            ->when($division, fn($q) => $q->where('sales_division', $division))
            ->whereDate('expected_delivery_date', '<', Carbon::today())
            ->whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->count();
        $delayedOrdersPrevious = (int) Sale::whereBetween('expected_delivery_date', [$prevStart, $prevEnd])
            ->whereDate('expected_delivery_date', '<', $prevEnd)
            ->whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->count();

        $activeCustomersCurrent = (int) Customer::when($regionState, fn($q) => $q->where('address_state', $regionState))
            ->when($regionCity, fn($q) => $q->where('address_city', $regionCity))
            ->whereIn('status', ['Ativo','active'])->count();
        $activeCustomersPrevious = $activeCustomersCurrent; 

        $assembliesToday = (int) AssemblySchedule::whereBetween('scheduled_date', [Carbon::today(), Carbon::today()->endOfDay()])->count();
        $assembliesYesterday = (int) AssemblySchedule::whereBetween('scheduled_date', [Carbon::yesterday(), Carbon::yesterday()->endOfDay()])->count();

        $vehiclesInOperationCurrent = (int) VehicleUsage::whereBetween('departure_date', [$currStart, $currEnd])->distinct('vehicle_id')->count('vehicle_id');
        $vehiclesInOperationPrevious = (int) VehicleUsage::whereBetween('departure_date', [$prevStart, $prevEnd])->distinct('vehicle_id')->count('vehicle_id');

        $operationalCostsCurrent = (float) Maintenance::whereBetween('maintenance_date', [$currStart, $currEnd])->sum('cost')
            + (float) FuelUp::whereBetween('fuel_up_date', [$currStart, $currEnd])->sum('total_value')
            + (float) VehicleFine::whereBetween('infraction_date', [$currStart, $currEnd])->sum('fine_amount');
        $operationalCostsPrevious = (float) Maintenance::whereBetween('maintenance_date', [$prevStart, $prevEnd])->sum('cost')
            + (float) FuelUp::whereBetween('fuel_up_date', [$prevStart, $prevEnd])->sum('total_value')
            + (float) VehicleFine::whereBetween('infraction_date', [$prevStart, $prevEnd])->sum('fine_amount');

        $npsQueryCurrent = AssemblyScheduleEvaluation::whereNotNull('submitted_at')
            ->whereHas('schedule', fn($q) => $q->whereBetween('scheduled_date', [$currStart, $currEnd]));
        $npsQueryPrevious = AssemblyScheduleEvaluation::whereNotNull('submitted_at')
            ->whereHas('schedule', fn($q) => $q->whereBetween('scheduled_date', [$prevStart, $prevEnd]));
        $npsCurrent = round((float) $npsQueryCurrent->avg('nps_score'), 2);
        $npsPrevious = round((float) $npsQueryPrevious->avg('nps_score'), 2);

        $statusDist = Sale::select('order_status', DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->get()
            ->mapWithKeys(function ($item) {
                $status = $item->order_status;
                if ($status instanceof \App\Enums\OrderStatusEnum) {
                    return [$status->label() => $item->total];
                }
                return [$status => $item->total];
            })
            ->toArray();

        $monthlyCurrentYear = [];
        $monthlyPrevYear = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyCurrentYear[Carbon::create(null, $m, 1)->format('M')] = (float) Sale::whereYear('issue_date', Carbon::now()->year)
                ->whereMonth('issue_date', $m)
                ->sum('grand_total');
            $monthlyPrevYear[Carbon::create(null, $m, 1)->format('M')] = (float) Sale::whereYear('issue_date', Carbon::now()->subYear()->year)
                ->whereMonth('issue_date', $m)
                ->sum('grand_total');
        }

        $assembliesLast30 = AssemblySchedule::whereBetween('scheduled_date', [Carbon::now()->subDays(30), Carbon::today()->endOfDay()])->get();
        $weekdayCounts = [ 'Dom'=>0,'Seg'=>0,'Ter'=>0,'Qua'=>0,'Qui'=>0,'Sex'=>0,'Sáb'=>0 ];
        foreach ($assembliesLast30 as $a) {
            $w = $a->scheduled_date ? Carbon::parse($a->scheduled_date)->dayOfWeekIso : 1; // 1=Mon ..7=Sun
            $labelMap = [7=>'Dom',1=>'Seg',2=>'Ter',3=>'Qua',4=>'Qui',5=>'Sex',6=>'Sáb'];
            $weekdayCounts[$labelMap[$w] ?? 'Seg']++;
        }

        $vehicleStatusDist = Vehicle::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total','status')->toArray();
        $fleetUtilization = [
            'ativos' => (int) ($vehicleStatusDist['Ativo'] ?? ($vehicleStatusDist['Em Uso'] ?? 0)),
            'manutencao' => (int) ($vehicleStatusDist['Em Manutenção'] ?? ($vehicleStatusDist['Manutenção'] ?? 0)),
            'disponiveis' => (int) ($vehicleStatusDist['Disponível'] ?? ($vehicleStatusDist['Disponiveis'] ?? 0)),
        ];
        $fleetTotal = max(1, array_sum($fleetUtilization));
        $fleetUtilizationPct = [
            'ativos' => round(($fleetUtilization['ativos'] / $fleetTotal) * 100, 2),
            'manutencao' => round(($fleetUtilization['manutencao'] / $fleetTotal) * 100, 2),
            'disponiveis' => round(($fleetUtilization['disponiveis'] / $fleetTotal) * 100, 2),
        ];

        $shipments = PlannedShipment::whereBetween('planned_delivery_date', [$currStart, $currEnd])->get();
        $logPerf = [ 'on_time'=>0, 'late'=>0, 'pending'=>0, 'returns'=>0 ];
        foreach ($shipments as $s) {
            if ($s->actual_delivery_date) {
                if (Carbon::parse($s->actual_delivery_date)->lte(Carbon::parse($s->planned_delivery_date))) $logPerf['on_time']++;
                else $logPerf['late']++;
            } else {
                $logPerf['pending']++;
            }
        }
        $logPerf['returns'] = (int) Sale::whereBetween('issue_date', [$currStart, $currEnd])->where('delivery_status', 'Returned')->count();

        $recentOrders = Sale::whereBetween('issue_date', [$currStart, $currEnd])->latest('issue_date')->take(10)->get();
        $nextAssemblies = AssemblySchedule::where('scheduled_date', '>=', Carbon::now()->startOfDay())->orderBy('scheduled_date')->take(10)->get();

        $fleetEvents = [];
        $maint = Maintenance::whereBetween('maintenance_date', [$currStart, $currEnd])->latest('maintenance_date')->take(5)->get()->map(function($m){ return [ 'type'=>'Manutenção', 'date'=>$m->maintenance_date, 'value'=>$m->cost, 'vehicle_id'=>$m->vehicle_id ]; });
        $fines = VehicleFine::whereBetween('infraction_date', [$currStart, $currEnd])->latest('infraction_date')->take(5)->get()->map(function($f){ return [ 'type'=>'Multa', 'date'=>$f->infraction_date, 'value'=>$f->fine_amount, 'vehicle_id'=>$f->vehicle_id ]; });
        $fuels = FuelUp::whereBetween('fuel_up_date', [$currStart, $currEnd])->latest('fuel_up_date')->take(5)->get()->map(function($fu){ return [ 'type'=>'Abastecimento', 'date'=>$fu->fuel_up_date, 'value'=>$fu->total_value, 'vehicle_id'=>$fu->vehicle_id ]; });
        $fleetEvents = collect()->merge($maint)->merge($fines)->merge($fuels)->sortByDesc('date')->take(10)->values()->all();

        $daysPassed = (int) Carbon::now()->day;
        $daysInMonth = (int) Carbon::now()->daysInMonth;
        $revenueToDate = (float) Sale::whereBetween('issue_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('grand_total');
        $dailyAvg = $daysPassed ? ($revenueToDate / $daysPassed) : 0;
        $projectionRevenue = round($dailyAvg * $daysInMonth, 2);

        $avgTicket = $salesFilter->clone()->count() ? round((float)$salesFilter->clone()->avg('grand_total'), 2) : 0;
        $marginAvg = null;

        $sparkMonthly = [];
        for ($i = 15; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $sparkMonthly[$d->format('d/m')] = (float) Sale::whereDate('issue_date', $d->toDateString())->sum('grand_total');
        }

        $representatives = Representative::all();

        return view('admin.general.dashboard', [
            'filters' => [
                'period' => $period ?: 'month',
                'start' => $start,
                'end' => $end,
                'representativeId' => $representativeId,
                'division' => $division,
                'region_state' => $regionState,
                'region_city' => $regionCity,
            ],
            'cards' => [
                'salesTotal' => $salesTotalCurrent,
                'salesTotalVar' => $this->variation($salesTotalCurrent, $salesTotalPrevious),
                'openOrders' => $openOrdersCurrent,
                'openOrdersVar' => $this->variation($openOrdersCurrent, $openOrdersPrevious),
                'delayedOrders' => $delayedOrdersCurrent,
                'delayedOrdersVar' => $this->variation($delayedOrdersCurrent, $delayedOrdersPrevious),
                'activeCustomers' => $activeCustomersCurrent,
                'activeCustomersVar' => 0.0,
                'assembliesToday' => $assembliesToday,
                'assembliesTodayVar' => $this->variation($assembliesToday, $assembliesYesterday),
                'vehiclesInOperation' => $vehiclesInOperationCurrent,
                'vehiclesInOperationVar' => $this->variation($vehiclesInOperationCurrent, $vehiclesInOperationPrevious),
                'operationalCosts' => $operationalCostsCurrent,
                'operationalCostsVar' => $this->variation($operationalCostsCurrent, $operationalCostsPrevious),
                'nps' => $npsCurrent,
                'npsVar' => $this->variation($npsCurrent, $npsPrevious),
            ],
            'statusDist' => $statusDist,
            'monthlyCurrentYear' => $monthlyCurrentYear,
            'monthlyPrevYear' => $monthlyPrevYear,
            'weekdayCounts' => $weekdayCounts,
            'fleetUtilizationPct' => $fleetUtilizationPct,
            'logPerf' => $logPerf,
            'recentOrders' => $recentOrders,
            'nextAssemblies' => $nextAssemblies,
            'fleetEvents' => $fleetEvents,
            'finance' => [
                'revenueToDate' => $revenueToDate,
                'projectionRevenue' => $projectionRevenue,
                'avgTicket' => $avgTicket,
                'marginAvg' => $marginAvg,
                'sparkMonthly' => $sparkMonthly,
            ],
            'representatives' => $representatives,
        ]);
    }
}


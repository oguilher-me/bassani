<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Maintenance;
use App\Models\FuelUp;
use App\Models\VehicleUsage;
use App\Models\VehicleFine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FleetDashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $vehicleId = $request->input('vehicle_id');
        $driverId = $request->input('driver_id');
        $status = $request->input('status');
        $costType = $request->input('cost_type');

        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        $vehicleQuery = Vehicle::query();
        if ($status) $vehicleQuery->where('status', $status);
        if ($vehicleId) $vehicleQuery->where('id', $vehicleId);
        $vehicleList = $vehicleQuery->get();

        $fuQuery = FuelUp::query()->when($start && $end, function($q) use ($start,$end){
            $q->whereBetween('fuel_up_date', [$start, $end]);
        })->when($vehicleId, function($q) use ($vehicleId){ $q->where('vehicle_id', $vehicleId); });
        $fuelUps = $fuQuery->get();

        $maintQuery = Maintenance::query()->when($start && $end, function($q) use ($start,$end){
            $q->whereBetween('maintenance_date', [$start, $end]);
        })->when($vehicleId, function($q) use ($vehicleId){ $q->where('vehicle_id', $vehicleId); });
        $maintenances = $maintQuery->get();

        $usageQuery = VehicleUsage::query()->when($start && $end, function($q) use ($start,$end){
            $q->whereBetween('usage_date', [$start, $end]);
        })->when($vehicleId, function($q) use ($vehicleId){ $q->where('vehicle_id', $vehicleId); });
        $vehicleUsages = $usageQuery->get();

        $finesQuery = VehicleFine::query()->when($start && $end, function($q) use ($start,$end){
            $q->whereBetween('infraction_date', [$start, $end]);
        })->when($vehicleId, function($q) use ($vehicleId){ $q->where('vehicle_id', $vehicleId); })
          ->when($driverId, function($q) use ($driverId){ $q->where('driver_id', $driverId); });
        $vehicleFines = $finesQuery->get();

        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'Ativo')->count();
        $unavailableVehicles = max(0, $totalVehicles - $availableVehicles);
        $pendingMaintenances = Maintenance::where('status', '!=', 'Concluída')->count();
        $overdueMaintenances = Maintenance::where('status', '!=', 'Concluída')->where('maintenance_date', '<', Carbon::now())->count();

        $kmMonth = $vehicleUsages->sum(function($u){
            $dep = (int)($u->departure_mileage ?? 0);
            $ret = (int)($u->return_mileage ?? 0);
            return max(0, $ret - $dep);
        });
        $fuelCostMonth = $fuelUps->sum('total_value');
        $maintCostMonth = $maintenances->sum('cost');
        $fleetCostMonth = $fuelCostMonth + $maintCostMonth;
        $avgFuelConsumption = $fuelUps->avg('consumption_km_l');
        $avgCostPerKm = $kmMonth > 0 ? round($fleetCostMonth / $kmMonth, 2) : 0;
        $totalFinesPeriod = $vehicleFines->count();

        $healthAlerts = [
            'overdue' => Maintenance::with('vehicle')->where('status','!=','Concluída')->where('maintenance_date','<',Carbon::now())->get(),
            'upcomingMileage' => Vehicle::whereNotNull('next_preventive_maintenance_mileage')->orderBy('next_preventive_maintenance_mileage','asc')->take(10)->get(),
            'docsDue' => Vehicle::where(function($q){
                $q->whereDate('licensing_due_date','<',Carbon::now()->addDays(30))
                  ->orWhereDate('insurance_due_date','<',Carbon::now()->addDays(30));
            })->get(),
            'topCost' => Vehicle::withSum(['maintenances' => function($q) use ($start,$end){ if($start&&$end){ $q->whereBetween('maintenance_date',[$start,$end]); } }], 'cost')
                              ->orderByDesc('maintenances_sum_cost')->take(10)->get(),
        ];

        $monthlyCosts = [
            'labels' => [], 'fuel' => [], 'maint' => [], 'fines' => []
        ];
        for ($i=11; $i>=0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $label = $month->format('M/Y');
            $monthlyCosts['labels'][] = $label;
            $monthlyCosts['fuel'][] = FuelUp::whereYear('fuel_up_date',$month->year)->whereMonth('fuel_up_date',$month->month)->sum('total_value');
            $monthlyCosts['maint'][] = Maintenance::whereYear('maintenance_date',$month->year)->whereMonth('maintenance_date',$month->month)->sum('cost');
            $monthlyCosts['fines'][] = VehicleFine::whereYear('infraction_date',$month->year)->whereMonth('infraction_date',$month->month)->sum('fine_amount');
        }

        $consumptionByVehicle = [];
        foreach ($vehicles as $v) {
            $avg = $v->fuelUps()->avg('consumption_km_l');
            $consumptionByVehicle[] = ['name'=>$v->placa.' '.$v->modelo, 'avg'=>$avg ?: 0];
        }

        $driversPerformance = [];
        foreach ($drivers as $d) {
            $usages = \App\Models\VehicleUsage::where('driver_id', $d->id)->get();
            $hours = $usages->sum(function($u){
                $dep = $u->departure_date ? Carbon::parse($u->departure_date) : null;
                $ret = $u->return_date ? Carbon::parse($u->return_date) : null;
                return ($dep && $ret) ? $dep->diffInHours($ret) : 0;
            });
            $kms = $usages->sum(function($u){
                $dep = (int)($u->departure_mileage ?? 0);
                $ret = (int)($u->return_mileage ?? 0);
                return max(0, $ret - $dep);
            });
            $fines = \App\Models\VehicleFine::where('driver_id', $d->id)->count();
            $driversPerformance[] = ['name'=>$d->full_name ?? $d->name, 'hours'=>$hours ?: 0, 'kms'=>$kms ?: 0, 'fines'=>$fines];
        }

        return view('admin.fleet.dashboard', [
            'filters' => compact('start','end','vehicleId','driverId','status','costType'),
            'cards' => [
                'totalVehicles' => $totalVehicles,
                'availableVehicles' => $availableVehicles,
                'unavailableVehicles' => $unavailableVehicles,
                'pendingMaintenances' => $pendingMaintenances,
                'overdueMaintenances' => $overdueMaintenances,
                'kmMonth' => $kmMonth,
                'fleetCostMonth' => $fleetCostMonth,
                'avgFuelConsumption' => round($avgFuelConsumption ?? 0, 2),
                'avgCostPerKm' => $avgCostPerKm,
                'totalFinesPeriod' => $totalFinesPeriod,
            ],
            'healthAlerts' => $healthAlerts,
            'monthlyCosts' => $monthlyCosts,
            'consumptionByVehicle' => $consumptionByVehicle,
            'driversPerformance' => $driversPerformance,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
        ]);
    }
}

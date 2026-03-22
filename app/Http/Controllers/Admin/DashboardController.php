<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Models\FuelUp;
use App\Models\VehicleUsage;
use App\Models\Driver;
use App\Models\Sale;
use App\Models\VehicleFine;
use App\Enums\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vehicleStatusDistribution = $this->getVehicleStatusDistribution();
        $monthlyMaintenanceCosts = $this->getMonthlyMaintenanceCosts();
        $averageFuelConsumption = $this->getAverageFuelConsumption();
        $top5MaintenanceCostVehicles = $this->getTop5MaintenanceCostVehicles();
        $vehicles = $this->getVehiclesData();

        // Variáveis de Resumo
        $totalVehicles = Vehicle::count();
        $totalMaintenances = Maintenance::count();
        $totalFuelUps = FuelUp::count();
        $totalDrivers = Driver::count();
        $totalVehicleUsages = VehicleUsage::count();

        // Variáveis de Resumo de Vendas
        $totalSalesAmount = $this->getTotalSales();
        $salesStatusDistribution = $this->getSalesStatusDistribution();
        $monthlySales = $this->getMonthlySales();
        $totalMaintenanceCost = $this->getTotalMaintenanceCost();
        $totalFuelUpCost = $this->getTotalFuelUpCost();
        $totalVehicleExpenses = $this->getTotalVehicleExpenses();
        $totalProfit = $this->getTotalProfit();

        // Variáveis de Multas
        $totalFines = $this->getTotalFines();
        $totalFinesByMonth = $this->getTotalFinesByMonth();
        $totalPendingFines = $this->getTotalPendingFines();
        $totalPendingFinesAmount = $this->getTotalPendingFinesAmount();
        $fineTypeDistribution = $this->getFineTypeDistribution();
        $latestFines = $this->getLatestFines();

        // Alertas e Notificações
        $upcomingDueDateThreshold = 30; // Dias para considerar como 'próximo do vencimento'

        $upcomingLicensing = Vehicle::where('licensing_due_date', '<=', now()->addDays($upcomingDueDateThreshold))
            ->where('licensing_due_date', '>=', now())
            ->get(['placa', 'modelo', 'licensing_due_date']);

        $upcomingInsurance = Vehicle::where('insurance_due_date', '<=', now()->addDays($upcomingDueDateThreshold))
            ->where('insurance_due_date', '>=', now())
            ->get(['placa', 'modelo', 'insurance_due_date']);

        // TODO: Implementar lógica para manutenção preventiva próxima e anomalias
        $upcomingPreventiveMaintenance = Vehicle::whereNotNull('next_preventive_maintenance_mileage')
            ->where(function ($query) {
                $query->whereRaw('quilometragem_atual >= next_preventive_maintenance_mileage - 1000')
                      ->orWhereRaw('quilometragem_atual >= next_preventive_maintenance_mileage');
            })
            ->get(['placa', 'modelo', 'quilometragem_atual', 'next_preventive_maintenance_mileage']);

        // TODO: Implementar lógica para anomalias

            $overdueMaintenances = Maintenance::where('maintenance_date', '<=', now())
            ->whereNotIn('status', ['Concluída', 'Cancelada'])
            ->with('vehicle')
            ->get();

        return view('welcome', compact(
            'vehicleStatusDistribution',
            'monthlyMaintenanceCosts',
            'averageFuelConsumption',
            'top5MaintenanceCostVehicles',
            'vehicles',
            'upcomingLicensing',
            'upcomingInsurance',
            'upcomingPreventiveMaintenance',
            'overdueMaintenances',
            'totalVehicles',
            'totalMaintenances',
            'totalFuelUps',
            'totalDrivers',
            'totalVehicleUsages',
            'totalSalesAmount',
            'salesStatusDistribution',
            'monthlySales',
            'totalFines',
            'totalFinesByMonth',
            'totalPendingFines',
            'fineTypeDistribution',
            'latestFines',
            'totalPendingFinesAmount',
            'totalMaintenanceCost',
            'totalFuelUpCost',
            'totalVehicleExpenses',
            'totalProfit'
        ));
    }

    private function getVehicleStatusDistribution()
    {
        return Vehicle::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    private function getMonthlyMaintenanceCosts()
    {
        $monthlyMaintenanceCosts = Maintenance::select(
            DB::raw('DATE_FORMAT(maintenance_date, "%Y-%m") as month'),
            DB::raw('SUM(cost) as total_cost')
        )
        ->where('maintenance_date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total_cost', 'month')
        ->toArray();

        // Fill in missing months with 0
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months[$month] = $monthlyMaintenanceCosts[$month] ?? 0;
        }
        return $months;
    }

    private function getAverageFuelConsumption()
    {
        return FuelUp::selectRaw('YEAR(fuel_up_date) as year, MONTH(fuel_up_date) as month, AVG(quantity) as avg_liters')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create($item->year, $item->month)->format('M/Y') => round($item->avg_liters, 2)];
            })
            ->toArray();
    }

    private function getTop5MaintenanceCostVehicles()
    {
        return Vehicle::withSum('maintenances', 'cost')
            ->orderByDesc('maintenances_sum_cost')
            ->take(5)
            ->get();
    }

    private function getVehiclesData()
    {
        return Vehicle::with(['maintenances' => function($query) {
            $query->orderBy('maintenance_date', 'desc');
        }, 'driver'])
        ->get()
        ->map(function ($vehicle) {
            $lastMaintenance = $vehicle->maintenances->first();
            $nextMaintenance = $vehicle->maintenances->where('maintenance_date', '>', now())->sortBy('maintenance_date')->first();
            return [
                'plate' => $vehicle->placa,
                'model' => $vehicle->modelo,
                'status' => $vehicle->status,
                'last_maintenance' => $lastMaintenance ? $lastMaintenance->maintenance_date->format('d/m/Y') : 'N/A',
                'next_maintenance' => $nextMaintenance ? $nextMaintenance->maintenance_date->format('d/m/Y') : 'N/A',
                'mileage' => number_format($vehicle->current_mileage, 0, ',', '.'),
                'responsible_driver' => $vehicle->driver ? $vehicle->driver->name : 'N/A',
                'total_maintenances' => $vehicle->maintenances->count(),
                'id' => $vehicle->id // Para ações rápidas
            ];
        });
    }

    private function getTotalSales()
    {
        return Sale::sum('grand_total');
    }

    private function getTotalMaintenanceCost()
    {
        return Maintenance::sum('cost');
    }

    private function getTotalFuelUpCost()
    {
        return FuelUp::sum('total_value');
    }

    private function getTotalVehicleExpenses()
    {
        return $this->getTotalMaintenanceCost() + $this->getTotalFuelUpCost();
    }

    private function getTotalProfit()
    {
        return $this->getTotalSales() - $this->getTotalVehicleExpenses();
    }

    private function getSalesStatusDistribution()
    {
        return Sale::select('order_status', DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status')
            ->toArray();
    }

    private function getMonthlySales()
    {
        $monthlySales = Sale::select(
            DB::raw('DATE_FORMAT(issue_date, "%Y-%m") as month'),
            DB::raw('SUM(grand_total) as total_sales')
        )
        ->where('issue_date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total_sales', 'month')
        ->toArray();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months[$month] = $monthlySales[$month] ?? 0;
        }
        return $months;
    }

    private function getTotalFinesByMonth()
    {
        $monthlyFines = VehicleFine::select(
            DB::raw('DATE_FORMAT(infraction_date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total_fines')
        )
        ->where('infraction_date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total_fines', 'month')
        ->toArray();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months[$month] = $monthlyFines[$month] ?? 0;
        }
        return $months;
    }

    private function getTotalPendingFines()
    {
        return VehicleFine::where('payment_status', PaymentStatus::Pending->value)->count();
    }

    private function getFineTypeDistribution()
    {
        return VehicleFine::select('fine_type', DB::raw('count(*) as total'))
            ->groupBy('fine_type')
            ->pluck('total', 'fine_type')
            ->toArray();
    }

    private function getLatestFines()
    {
        return VehicleFine::with(['vehicle', 'driver'])
            ->orderByDesc('infraction_date')
            ->take(5)
            ->get();
    }

    private function getTotalFines()
    {
        return VehicleFine::count();
    }

    private function getTotalPendingFinesAmount()
    {
        return VehicleFine::where('payment_status', PaymentStatus::Pending->value)->sum('fine_amount');
    }
}
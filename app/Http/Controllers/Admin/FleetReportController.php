<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FuelUp;
use App\Models\Vehicle;

class FleetReportController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::all();
        $query = FuelUp::with('vehicle');

        // Aplicar filtros
        if ($request->filled('start_date')) {
            $query->whereDate('fuel_up_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('fuel_up_date', '<=', $request->input('end_date'));
        }
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->input('vehicle_id'));
        }
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->input('fuel_type'));
        }

        $fuelUps = $query->get();

        $fleetReport = [];
        foreach ($vehicles as $vehicle) {
            $vehicleFuelUps = $fuelUps->where('vehicle_id', $vehicle->id);

            if ($vehicleFuelUps->isNotEmpty()) {
                $totalQuantity = $vehicleFuelUps->sum('quantity');
                $totalValue = $vehicleFuelUps->sum('total_value');
                $totalDistance = $vehicleFuelUps->sum('distance_traveled');

                $averageConsumption = $totalQuantity > 0 ? $totalDistance / $totalQuantity : 0;
                $totalCost = $totalValue;

                $fleetReport[] = [
                    'vehicle' => $vehicle,
                    'average_consumption' => $averageConsumption,
                    'total_cost' => $totalCost,
                ];
            }
        }

        return view('admin.reports.fleet_report', compact('fleetReport', 'vehicles', 'request'));
    }

    public function vehicleDetailedReport(Request $request)
    {
        $vehicles = Vehicle::all();
        $selectedVehicle = null;
        $fuelUps = collect();

        if ($request->filled('vehicle_id')) {
            $selectedVehicle = Vehicle::findOrFail($request->input('vehicle_id'));

            $query = FuelUp::where('vehicle_id', $selectedVehicle->id);

            if ($request->filled('start_date')) {
                $query->whereDate('fuel_up_date', '>=', $request->input('start_date'));
            }
            if ($request->filled('end_date')) {
                $query->whereDate('fuel_up_date', '<=', $request->input('end_date'));
            }

            $fuelUps = $query->orderBy('fuel_up_date', 'asc')->get();
        }

        return view('admin.reports.vehicle_detailed_report', compact('fuelUps', 'vehicles', 'selectedVehicle', 'request'));
    }
}

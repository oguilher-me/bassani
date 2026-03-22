<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleUsage;
use App\Models\CarBrand;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carBrands = CarBrand::all();
        return view('admin.vehicles.create', compact('carBrands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:8|unique:vehicles,placa',
            'modelo' => 'required|string|max:255',
            'car_brand_id' => 'required|exists:car_brands,id',
            'ano_fabricacao' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'quilometragem_atual' => 'required|numeric|min:0',
            'status' => 'required|string|in:Ativo,Em manutenção,Inativo',
            'data_aquisicao' => 'required|date',
            'observacoes' => 'nullable|string|max:1000',
            'cubic_capacity' => 'nullable|numeric|min:0',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicleUsages = $vehicle->vehicleUsages()->with('driver')->get();
        $fuelUps = $vehicle->fuelUps()->orderBy('fuel_up_date', 'desc')->get();
        $maintenances = $vehicle->maintenances()->orderBy('maintenance_date', 'desc')->get();
        $vehicleFines = $vehicle->vehicleFines()->orderBy('infraction_date', 'desc')->get();

        $lastMaintenance = $vehicle->maintenances()
            ->where('status', 'Concluída') // Adiciona filtro para status 'Concluída'
            ->orderByDesc('maintenance_date')
            ->first();

        $upcomingMaintenances = $vehicle->maintenances()
            ->where('maintenance_date', '>', now())
            ->orderBy('maintenance_date')
            ->get();

        // Calculate average fuel consumption
        $totalFuelQuantity = $vehicle->fuelUps()->sum('quantity');
        $totalDistance = $vehicle->vehicleUsages()->sum(\DB::raw('return_mileage - departure_mileage'));
        $averageFuelConsumption = ($totalDistance > 0) ? ($totalFuelQuantity / $totalDistance) * 100 : 0;

        // Calculate annual costs (example: current year)
        $currentYear = now()->year;
        $annualMaintenanceCosts = $vehicle->maintenances()
            ->whereYear('maintenance_date', $currentYear)
            ->sum('cost');
        $annualFuelCosts = $vehicle->fuelUps()
            ->whereYear('fuel_up_date', $currentYear)
            ->sum('total_value');
        $annualCosts = $annualMaintenanceCosts + $annualFuelCosts;

        return view('admin.vehicles.show', compact('vehicle', 'vehicleUsages', 'fuelUps', 'maintenances', 'vehicleFines', 'lastMaintenance', 'upcomingMaintenances', 'averageFuelConsumption', 'annualCosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $carBrands = CarBrand::all();
        return view('admin.vehicles.edit', compact('vehicle', 'carBrands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'placa' => 'required|string|max:8|unique:vehicles,placa,' . $vehicle->id,
            'modelo' => 'required|string|max:255',
            'car_brand_id' => 'required|exists:car_brands,id',
            'ano_fabricacao' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'quilometragem_atual' => 'required|integer|min:0',
            'status' => 'required|in:Ativo,Em manutenção,Inativo',
            'data_aquisicao' => 'required|date',
            'observacoes' => 'nullable|string',
            'cubic_capacity' => 'nullable|numeric|min:0',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Veículo excluído com sucesso!');
    }
}

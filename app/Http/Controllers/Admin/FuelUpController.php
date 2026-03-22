<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuelUp;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FuelUpController extends Controller
{
    public function index()
    {
        $fuelUps = FuelUp::with('vehicle')->orderBy('fuel_up_date', 'desc')->get();
        return view('admin.fuel_ups.index', compact('fuelUps'));
    }

    public function create(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        $vehicle = null;
        if ($vehicleId) {
            $vehicle = Vehicle::findOrFail($vehicleId);
        }
        $vehicles = Vehicle::all();
        return view('admin.fuel_ups.create', compact('vehicle', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_up_date' => 'required|date',
            'fuel_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'total_value' => 'required|numeric|min:0.01',
            'current_km' => 'required|integer|min:0',
            'fuel_up_type' => 'required|string|max:255',
            'station_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:255',
            'observations' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

        // Validação da quilometragem atual
        $lastFuelUp = FuelUp::where('vehicle_id', $vehicle->id)
            ->orderBy('fuel_up_date', 'desc')
            ->first();

        if ($lastFuelUp && $validatedData['current_km'] <= $lastFuelUp->current_km) {
            return redirect()->back()->withErrors(['current_km' => 'A quilometragem atual deve ser maior que a quilometragem do último abastecimento (' . $lastFuelUp->current_km . ' KM).'])->withInput();
        }

        // Calcular previous_km, distance_traveled, consumption_km_l, cost_per_km
        $previousKm = $lastFuelUp ? $lastFuelUp->current_km : $vehicle->initial_km;
        $distanceTraveled = $validatedData['current_km'] - $previousKm;
        $consumptionKmL = $distanceTraveled > 0 ? $distanceTraveled / $validatedData['quantity'] : 0;
        $costPerKm = $distanceTraveled > 0 ? $validatedData['total_value'] / $distanceTraveled : 0;

        $fuelUp = FuelUp::create(array_merge($validatedData, [
            'unit_value' => $validatedData['total_value'] / $validatedData['quantity'],
            'previous_km' => $previousKm,
            'distance_traveled' => $distanceTraveled,
            'consumption_km_l' => $consumptionKmL,
            'cost_per_km' => $costPerKm,
        ]));

        return redirect()->route('vehicles.show', $fuelUp->vehicle_id)->with('success', 'Abastecimento registrado com sucesso!');
    }

    public function show(FuelUp $fuelUp)
    {
        return view('admin.fuel_ups.show', compact('fuelUp'));
    }

    public function edit(FuelUp $fuelUp)
    {
        $vehicles = Vehicle::all();
        return view('admin.fuel_ups.edit', compact('fuelUp', 'vehicles'));
    }

    public function update(Request $request, FuelUp $fuelUp)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_up_date' => 'required|date',
            'fuel_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'total_value' => 'required|numeric|min:0.01',
            'current_km' => 'required|integer|min:0',
            'fuel_up_type' => 'required|string|max:255',
            'station_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:255',
            'observations' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

        // Validação da quilometragem atual
        $lastFuelUp = FuelUp::where('vehicle_id', $vehicle->id)
            ->where('id', '!=', $fuelUp->id) // Excluir o abastecimento atual da verificação
            ->orderBy('fuel_up_date', 'desc')
            ->first();

        if ($lastFuelUp && $validatedData['current_km'] <= $lastFuelUp->current_km) {
            return redirect()->back()->withErrors(['current_km' => 'A quilometragem atual deve ser maior que a quilometragem do último abastecimento (' . $lastFuelUp->current_km . ' KM).'])->withInput();
        }

        // Calcular previous_km, distance_traveled, consumption_km_l, cost_per_km
        $previousKm = $lastFuelUp ? $lastFuelUp->current_km : $vehicle->initial_km;
        $distanceTraveled = $validatedData['current_km'] - $previousKm;
        $consumptionKmL = $distanceTraveled > 0 ? $distanceTraveled / $validatedData['quantity'] : 0;
        $costPerKm = $distanceTraveled > 0 ? $validatedData['total_value'] / $distanceTraveled : 0;

        $fuelUp->update(array_merge($validatedData, [
            'unit_value' => $validatedData['total_value'] / $validatedData['quantity'],
            'previous_km' => $previousKm,
            'distance_traveled' => $distanceTraveled,
            'consumption_km_l' => $consumptionKmL,
            'cost_per_km' => $costPerKm,
        ]));

        return redirect()->route('vehicles.show', $fuelUp->vehicle_id)->with('success', 'Abastecimento atualizado com sucesso!');
    }

    public function destroy(FuelUp $fuelUp)
    {
        $vehicleId = $fuelUp->vehicle_id;
        $fuelUp->delete();
        return redirect()->route('vehicles.show', $vehicleId)->with('success', 'Abastecimento excluído com sucesso!');  
    }
}

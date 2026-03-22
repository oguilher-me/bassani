<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleUsage;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class VehicleUsageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleUsages = VehicleUsage::with(['vehicle', 'driver'])->latest()->paginate(10);
        return view('admin.vehicle_usages.index', compact('vehicleUsages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        return view('admin.vehicle_usages.create', compact('vehicles', 'drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'departure_date' => 'required|date',
            'departure_mileage' => 'required|integer',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'return_mileage' => 'nullable|integer|gte:departure_mileage',
            'route_destination' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'trip_status' => 'required|in:Em andamento,Finalizada',
        ]);

        VehicleUsage::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Uso de veículo registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleUsage $vehicleUsage)
    {
        return view('admin.vehicle_usages.show', compact('vehicleUsage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleUsage $vehicleUsage)
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        return view('admin.vehicle_usages.edit', compact('vehicleUsage', 'vehicles', 'drivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleUsage $vehicleUsage)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'departure_date' => 'required|date',
            'departure_mileage' => 'required|integer',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'return_mileage' => 'nullable|integer|gte:departure_mileage',
            'route_destination' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'trip_status' => 'required|in:Em andamento,Finalizada',
        ]);

        $vehicleUsage->update($request->all());

        return redirect()->route('vehicle_usages.index')->with('success', 'Uso de veículo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleUsage $vehicleUsage)
    {
        $vehicleUsage->delete();
        return redirect()->route('vehicle_usages.index')->with('success', 'Uso de veículo excluído com sucesso!');
    }
}

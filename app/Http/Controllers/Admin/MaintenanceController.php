<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Maintenance::with('vehicle');

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('maintenance_date', [$request->start_date, $request->end_date]);
        }

        $maintenances = $query->orderBy('maintenance_date', 'desc')->paginate(10);
        $vehicles = Vehicle::all();

        return view('admin.maintenances.index', compact('maintenances', 'vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $vehicles = Vehicle::all();
        $selectedVehicleId = $request->query('vehicle_id');
        return view('admin.maintenances.create', compact('vehicles', 'selectedVehicleId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string|max:255',
            'maintenance_date' => 'required|date',
            'mileage' => 'required|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'supplier' => 'nullable|string|max:255',
            'status' => 'required|string|in:Agendada,Em execução,Concluída,Cancelada',
            'service_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'observations' => 'nullable|string',
        ]);

        $maintenanceData = $request->except('service_proof');

        if ($request->hasFile('service_proof')) {
            $path = $request->file('service_proof')->store('service_proofs', 'public');
            $maintenanceData['service_proof'] = $path;
        }

        $maintenance = Maintenance::create($maintenanceData);

        // Lógica para atualizar a próxima quilometragem de manutenção preventiva
        if ($maintenance->type === 'Preventiva') {
            $vehicle = Vehicle::find($maintenance->vehicle_id);
            if ($vehicle) {
                // Define a próxima manutenção preventiva para 10.000 KM após a manutenção atual
                $vehicle->next_preventive_maintenance_mileage = $maintenance->mileage + 10000;
                $vehicle->save();
            }
        }

        return redirect()->route('vehicles.show', $maintenance->vehicle_id)->with('success', __('Manutenção registrada com sucesso!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        return view('admin.maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        $vehicles = Vehicle::all();
        return view('admin.maintenances.edit', compact('maintenance', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string|max:255',
            'maintenance_date' => 'required|date',
            'mileage' => 'required|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'supplier' => 'nullable|string|max:255',
            'status' => 'required|string|in:Agendada,Em execução,Concluída,Cancelada',
            'service_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $maintenanceData = $request->except('service_proof');

        if ($request->hasFile('service_proof')) {
            // Deleta o comprovante antigo se existir
            if ($maintenance->service_proof) {
                Storage::disk('public')->delete($maintenance->service_proof);
            }
            $path = $request->file('service_proof')->store('service_proofs', 'public');
            $maintenanceData['service_proof'] = $path;
        } else {
            // Mantém o comprovante existente se nenhum novo for enviado
            $maintenanceData['service_proof'] = $maintenance->service_proof;
        }

        $maintenance->update($maintenanceData);

        // Lógica para atualizar a próxima quilometragem de manutenção preventiva
        if ($maintenance->type === 'Preventiva') {
            $vehicle = Vehicle::find($maintenance->vehicle_id);
            if ($vehicle && $maintenance->isDirty('mileage')) { // Verifica se a quilometragem foi alterada
                // Define a próxima manutenção preventiva para 10.000 KM após a manutenção atual
                $vehicle->next_preventive_maintenance_mileage = $maintenance->mileage + 10000;
                $vehicle->save();
            }
        }

        return redirect()->route('vehicles.show', $maintenance->vehicle_id)->with('success', __('Manutenção atualizada com sucesso!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        if ($maintenance->service_proof) {
            Storage::disk('public')->delete($maintenance->service_proof);
        }
        $maintenance->delete();

        return redirect()->route('vehicles.show', $maintenance->vehicle_id)->with('success', __('Manutenção excluída com sucesso!'));
    }
}

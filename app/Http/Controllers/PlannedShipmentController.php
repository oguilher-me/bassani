<?php

namespace App\Http\Controllers;

use App\Models\PlannedShipment;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Sale;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlannedShipmentController extends Controller
{
    public function index()
    {
        return view('admin.planned_shipments.index');
    }

    public function data()
    {
        $data = PlannedShipment::with(['vehicle', 'driver'])
            ->select('planned_shipments.shipment_id', 'planned_shipments.shipment_number', 'planned_shipments.vehicle_id', 'planned_shipments.driver_id', 'planned_shipments.planned_departure_date', 'planned_shipments.planned_delivery_date', 'planned_shipments.status');
        
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $orders = Sale::with(['customer','saleItems.product'])->get();
        return view('admin.planned_shipments.create', compact('vehicles', 'drivers', 'orders'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_number' => 'required|string|unique:planned_shipments',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'planned_departure_date' => 'required|date',
            'status' => 'required|in:Planned,In Transit,Delivered,Returned,Cancelled',
            'total_weight' => 'nullable|numeric',
            'total_volume' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'items' => 'array',
            'items.*' => 'exists:sale_items,id',
            'destinations_addresses' => 'array',
            'destinations_addresses.*' => 'string',
            'destinations_contact_names' => 'array',
            'destinations_contact_phones' => 'array',
            'destinations_window_starts' => 'array',
            'destinations_window_ends' => 'array',
        ]);

        $shipment = PlannedShipment::create($validated);

        // // Vincular itens de venda à carga planejada
        // if ($request->has('items')) {
        //     $shipment->saleItems()->sync($request->items);
        // }

        $addresses = $request->input('destinations_addresses', []);
        $names = $request->input('destinations_contact_names', []);
        $phones = $request->input('destinations_contact_phones', []);
        $starts = $request->input('destinations_window_starts', []);
        $ends = $request->input('destinations_window_ends', []);
        
        foreach ($addresses as $i => $addr) {
            if (!$addr) continue;
            $dest = $shipment->destinations()->create([
                'address' => $addr,
                'contact_name' => $names[$i] ?? null,
                'contact_phone' => $phones[$i] ?? null,
                'window_start' => $starts[$i] ?? null,
                'window_end' => $ends[$i] ?? null,
            ]);
            $destItems = $request->input("destinations_items.$i", []);
            if (!empty($destItems)) {
                $dest->items()->sync($destItems);
            }
        }

        return redirect()->route('planned_shipments.index')->with('success', 'Carga planejada criada com sucesso.');
    }

    public function show(PlannedShipment $plannedShipment)
    {
        $plannedShipment->load(['vehicle', 'driver', 'sales', 'tracking']);
        return view('admin.planned_shipments.show', compact('plannedShipment'));
    }

    public function edit(PlannedShipment $plannedShipment)
    {
        $plannedShipment->load(['destinations']);
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $orders = Sale::with(['customer','saleItems.product'])->get();
        return view('admin.planned_shipments.edit', compact('plannedShipment', 'vehicles', 'drivers', 'orders'));
    }

    public function update(Request $request, PlannedShipment $plannedShipment)
    {
        $validated = $request->validate([
            'shipment_number' => 'required|string|unique:planned_shipments,shipment_number,' . $plannedShipment->shipment_id . ',shipment_id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'planned_departure_date' => 'required|date',
            'status' => 'required|in:Planned,In Transit,Delivered,Returned,Cancelled',
            'total_weight' => 'nullable|numeric',
            'total_volume' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'items' => 'array',
            'items.*' => 'exists:sale_items,id',
            'destinations_addresses' => 'array',
            'destinations_addresses.*' => 'string',
            'destinations_contact_names' => 'array',
            'destinations_contact_phones' => 'array',
            'destinations_window_starts' => 'array',
            'destinations_window_ends' => 'array',
        ]);

        $plannedShipment->update($validated);

        // Atualizar itens vinculados
        //$plannedShipment->saleItems()->sync($request->items ?? []);

        $plannedShipment->destinations()->delete();
        $addresses = $request->input('destinations_addresses', []);
        $names = $request->input('destinations_contact_names', []);
        $phones = $request->input('destinations_contact_phones', []);
        $starts = $request->input('destinations_window_starts', []);
        $ends = $request->input('destinations_window_ends', []);
        foreach ($addresses as $i => $addr) {
            if (!$addr) continue;
            $dest = $plannedShipment->destinations()->create([
                'address' => $addr,
                'contact_name' => $names[$i] ?? null,
                'contact_phone' => $phones[$i] ?? null,
                'window_start' => $starts[$i] ?? null,
                'window_end' => $ends[$i] ?? null,
            ]);
            $destItems = $request->input("destinations_items.$i", []);
            if (!empty($destItems)) {
                $dest->items()->sync($destItems);
            }
        }

        return redirect()->route('planned_shipments.index')->with('success', 'Carga planejada atualizada com sucesso.');
    }

    public function destroy(PlannedShipment $plannedShipment)
    {
        $plannedShipment->delete();
        return redirect()->route('planned_shipments.index')->with('success', 'Carga planejada excluída com sucesso.');
    }
}

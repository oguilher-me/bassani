<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentTracking;
use Illuminate\Http\Request;

class ShipmentTrackingController extends Controller
{
    /**
     * Store a new tracking entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|exists:planned_shipments,shipment_id',
            'status' => 'required|in:In Transit,At Warehouse,Out for Delivery,Delivered',
            'location' => 'nullable|string'
        ]);

        ShipmentTracking::create($request->all());

        return redirect()->back()->with('success', 'Rastreamento adicionado com sucesso!');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleCheckup;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCheckupController extends Controller
{
    /**
     * Display a listing of all vehicle checkups with filters.
     */
    public function index(Request $request): View
    {
        $query = VehicleCheckup::with(['vehicle', 'user']);

        // Filter by vehicle
        if ($request->filled('vehicle_id')) {
            $query->byVehicle($request->vehicle_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date . ' 23:59:59');
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Order by most recent first
        $checkups = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get vehicles for filter dropdown
        $vehicles = Vehicle::orderBy('placa')->get();

        // Statistics
        $totalCheckups = VehicleCheckup::count();
        $passedCheckups = VehicleCheckup::passed()->count();
        $failedCheckups = VehicleCheckup::failed()->count();

        return view('admin.checkups.index', compact(
            'checkups',
            'vehicles',
            'totalCheckups',
            'passedCheckups',
            'failedCheckups'
        ));
    }

    /**
     * Display the details of a specific checkup.
     */
    public function show(VehicleCheckup $checkup): View
    {
        $checkup->load(['vehicle', 'user', 'responses.checklistItem']);

        return view('admin.checkups.show', compact('checkup'));
    }
}

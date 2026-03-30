<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $driver = $user->driver;

        if (! $driver) {
            return redirect('/login')->with('error', 'Você não está associado a um motorista.');
        }

        $dailyDestinations = ShipmentDestination::with(['plannedShipment.vehicle'])
            ->whereHas('plannedShipment', function ($q) use ($driver) {
                $q->where('driver_id', $driver->id);
            })
            ->whereDate('window_start', today())
            ->orderBy('window_start')
            ->get();

        $weeklyDestinations = ShipmentDestination::with(['plannedShipment.vehicle'])
            ->whereHas('plannedShipment', function ($q) use ($driver) {
                $q->where('driver_id', $driver->id);
            })
            ->whereDate('window_start', '>', today())
            ->whereDate('window_start', '<=', now()->addDays(7))
            ->orderBy('window_start')
            ->take(10)
            ->get();

        $pendingExpenses = DriverExpense::where('driver_id', $driver->id)
            ->where('status', 'pendente')
            ->count();

        $approvedExpenses = DriverExpense::where('driver_id', $driver->id)
            ->where('status', 'aprovado')
            ->sum('amount');

        return view('driver.home', compact('dailyDestinations', 'weeklyDestinations', 'pendingExpenses', 'approvedExpenses'));
    }
}

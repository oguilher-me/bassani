<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverExpense;
use App\Models\PlannedShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $driver = $user->driver;

        if (! $driver) {
            return redirect('/login')->with('error', 'Você não está associado a um motorista.');
        }

        $query = DriverExpense::where('driver_id', $driver->id);

        if ($request->filled('filter') && $request->filter !== 'all') {
            $query->where('status', $request->filter);
        }

        $expenses = $query->latest('date')->paginate(20)->withQueryString();

        $approvedTotal = DriverExpense::where('driver_id', $driver->id)
            ->where('status', 'aprovado')
            ->sum('amount');

        $pendingTotal = DriverExpense::where('driver_id', $driver->id)
            ->where('status', 'pendente')
            ->sum('amount');

        return view('driver.expenses.index', compact('expenses', 'approvedTotal', 'pendingTotal'));
    }

    public function create()
    {
        $user = Auth::user();
        $driver = $user->driver;

        if (! $driver) {
            return redirect('/login')->with('error', 'Você não está associado a um motorista.');
        }

        $shipments = PlannedShipment::where('driver_id', $driver->id)
            ->whereDate('planned_delivery_date', '<=', now()->addDays(1))
            ->whereDate('planned_delivery_date', '>=', now()->subDays(30))
            ->orderBy('planned_delivery_date', 'desc')
            ->get();

        return view('driver.expenses.create', compact('shipments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $driver = $user->driver;

        if (! $driver) {
            return back()->with('error', 'Você não está associado a um motorista.');
        }

        $request->validate([
            'shipment_id' => 'required|exists:planned_shipments,shipment_id',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'description' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|image|max:5120|dimensions:max_width=2000,max_height=2000',
        ]);

        $shipment = PlannedShipment::findOrFail($request->shipment_id);

        if ($shipment->driver_id != $driver->id) {
            return back()->with('error', 'Esta entrega não está atribuída a você.');
        }

        try {
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')
                    ->store("driver-receipts/{$shipment->shipment_id}", 'public');
            }

            DriverExpense::create([
                'shipment_id' => $shipment->shipment_id,
                'driver_id' => $driver->id,
                'category' => $request->category,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
                'receipt_path' => $receiptPath,
                'status' => 'pendente',
            ]);

            return redirect('/driver/expenses')->with('success', 'Despesa lançada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao lançar despesa: '.$e->getMessage());
        }
    }
}

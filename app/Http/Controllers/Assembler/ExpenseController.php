<?php

namespace App\Http\Controllers\Assembler;

use App\Http\Controllers\Controller;
use App\Models\AssemblySchedule;
use App\Models\AssemblyExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display list of assembler's expenses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $assembler = $user->assembler;

        if (!$assembler) {
            return redirect('/login')->with('error', 'Você não está associado a um montador.');
        }

        $query = AssemblyExpense::where('assembler_id', $assembler->id);

        // Apply filter
        if ($request->filled('filter') && $request->filter !== 'all') {
            $query->where('status', $request->filter);
        }

        $expenses = $query->latest('date')->paginate(20)->withQueryString();

        // Calculate totals
        $approvedTotal = AssemblyExpense::where('assembler_id', $assembler->id)
            ->where('status', 'aprovado')
            ->sum('amount');
        
        $pendingTotal = AssemblyExpense::where('assembler_id', $assembler->id)
            ->where('status', 'pendente')
            ->sum('amount');

        return view('assembler.expenses.index', compact('expenses', 'approvedTotal', 'pendingTotal'));
    }

    /**
     * Show form to create expense.
     */
    public function create()
    {
        $user = Auth::user();
        $assembler = $user->assembler;

        if (!$assembler) {
            return redirect('/login')->with('error', 'Você não está associado a um montador.');
        }

        // Get schedules assigned to this assembler
        $schedules = AssemblySchedule::whereHas('assemblers', function($q) use ($assembler) {
                $q->where('assemblers.id', $assembler->id);
            })
            ->whereDate('scheduled_date', '<=', now()->addDays(1))
            ->whereDate('scheduled_date', '>=', now()->subDays(30))
            ->orderBy('scheduled_date', 'desc')
            ->get();

        return view('assembler.expenses.create', compact('schedules'));
    }

    /**
     * Store a new expense.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $assembler = $user->assembler;

        if (!$assembler) {
            return back()->with('error', 'Você não está associado a um montador.');
        }

        $request->validate([
            'assembly_schedule_id' => 'required|exists:assembly_schedules,id',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'description' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|image|max:5120|dimensions:max_width=2000,max_height=2000',
        ]);

        // Verify assembler is assigned to this schedule
        $schedule = AssemblySchedule::with('assemblers')->findOrFail($request->assembly_schedule_id);
        $isAssigned = $schedule->assemblers->contains('id', $assembler->id);

        if (!$isAssigned) {
            return back()->with('error', 'Você não está atribuído a esta montagem.');
        }

        try {
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')
                    ->store("receipts/{$schedule->id}", 'public');
            }

            AssemblyExpense::create([
                'assembly_schedule_id' => $schedule->id,
                'assembler_id' => $assembler->id,
                'category' => $request->category,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
                'receipt_path' => $receiptPath,
                'status' => 'pendente',
            ]);

            return redirect('/assembler/expenses')->with('success', 'Despesa lançada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao lançar despesa: ' . $e->getMessage());
        }
    }
}

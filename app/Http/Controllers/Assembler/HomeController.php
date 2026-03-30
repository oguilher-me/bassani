<?php

namespace App\Http\Controllers\Assembler;

use App\Http\Controllers\Controller;
use App\Models\AssemblySchedule;
use App\Models\AssemblyExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the assembler home dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $assembler = $user->assembler;

        if (!$assembler) {
            return redirect('/login')->with('error', 'Você não está associado a um montador.');
        }

        // Today's schedules
        $todaySchedules = AssemblySchedule::with(['sale.customer'])
            ->whereHas('assemblers', function($q) use ($assembler) {
                $q->where('assemblers.id', $assembler->id);
            })
            ->whereDate('scheduled_date', today())
            ->orderBy('start_time')
            ->get();

        // Stats
        $todayCount = $todaySchedules->count();
        
        $weekCount = AssemblySchedule::whereHas('assemblers', function($q) use ($assembler) {
                $q->where('assemblers.id', $assembler->id);
            })
            ->whereBetween('scheduled_date', [today(), now()->addDays(7)])
            ->count();

        $completedCount = AssemblySchedule::whereHas('assemblers', function($q) use ($assembler) {
                $q->where('assemblers.id', $assembler->id)
                  ->whereIn('confirmation_status', ['completed', 'completed_with_pendencies']);
            })
            ->count();

        $expensesTotal = AssemblyExpense::where('assembler_id', $assembler->id)
            ->where('status', 'aprovado')
            ->sum('amount');

        // Recent expenses
        $recentExpenses = AssemblyExpense::where('assembler_id', $assembler->id)
            ->latest()
            ->take(5)
            ->get();

        return view('assembler.home', compact(
            'todaySchedules',
            'todayCount',
            'weekCount', 
            'completedCount',
            'expensesTotal',
            'recentExpenses'
        ));
    }
}

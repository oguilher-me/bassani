<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssemblyExpense;
use App\Models\AssemblySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class AssemblyExpenseController extends Controller
{
    /**
     * List expenses — for admin: all expenses for a schedule (or all).
     * For assembler: only their own expenses.
     */
    public function index(Request $request)
    {
        $user      = Auth::user();
        $assembler = $user->assembler ?? null;

        $query = AssemblyExpense::with(['assemblySchedule.sale', 'assembler'])
            ->when($request->assembly_schedule_id, fn($q, $id) => $q->where('assembly_schedule_id', $id))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($assembler && !$user->hasRole('Admin'), fn($q) => $q->where('assembler_id', $assembler->id));

        $expenses = $query->latest()->paginate(20);

        return view('admin.assembly-expenses.index', compact('expenses'));
    }

    /**
     * Store a new expense submitted by an assembler.
     * Guarantees the schedule is assigned to the authenticated assembler.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assembly_schedule_id' => 'required|exists:assembly_schedules,id',
            'category'     => 'required|in:Alimentação,Hospedagem,Combustível,Pedágio,Estacionamento,Material Extra,Outros',
            'amount'       => 'required|numeric|min:0.01',
            'description'  => 'nullable|string|max:1000',
            'date'         => 'required|date|before_or_equal:today',
            'receipt'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user      = Auth::user();
        $assembler = $user->assembler;

        if (!$assembler) {
            return back()->with('error', 'Você não está associado a um montador.');
        }

        // Guarantees the assembler is assigned to this schedule
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
                'assembler_id'         => $assembler->id,
                'category'             => $request->category,
                'amount'               => $request->amount,
                'description'          => $request->description,
                'date'                 => $request->date,
                'receipt_path'         => $receiptPath,
                'status'               => 'pendente',
            ]);

            return back()->with('success', 'Despesa lançada com sucesso!');
        } catch (Throwable $e) {
            Log::error('AssemblyExpenseController@store: ' . $e->getMessage());
            return back()->with('error', 'Erro ao lançar despesa: ' . $e->getMessage());
        }
    }

    /**
     * Approve an expense (Admin / Financial).
     */
    public function approve(AssemblyExpense $expense)
    {
        $expense->update(['status' => 'aprovado', 'rejection_reason' => null]);
        return back()->with('success', 'Despesa aprovada com sucesso!');
    }

    /**
     * Reject an expense with a reason (Admin / Financial).
     */
    public function reject(Request $request, AssemblyExpense $expense)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $expense->update([
            'status'           => 'rejeitado',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Despesa rejeitada.');
    }

    /**
     * Delete an expense record and its receipt file.
     */
    public function destroy(AssemblyExpense $expense)
    {
        try {
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $expense->delete();
            return back()->with('success', 'Despesa removida com sucesso!');
        } catch (Throwable $e) {
            Log::error('AssemblyExpenseController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover despesa.');
        }
    }
}

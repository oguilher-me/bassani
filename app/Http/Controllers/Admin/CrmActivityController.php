<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmActivity;
use App\Models\CrmOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CrmActivityController extends Controller
{
    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request, CrmOpportunity $opportunity)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:call,email,meeting,task,whatsapp,visit',
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'status' => 'nullable|in:pending,completed,canceled'
            ]);

            $activity = new CrmActivity($validated);
            $activity->opportunity_id = $opportunity->id;
            $activity->user_id = auth()->id();
            
            if ($request->status === 'completed' || (!$request->due_date && $request->type !== 'task')) {
                $activity->status = 'completed';
                $activity->completed_at = now();
            } else {
                $activity->status = 'pending';
            }

            $activity->save();

            try {
                event(new \App\Events\CrmActivityCreated($activity));
            } catch (Throwable $e) {
                Log::error("Error triggering CrmActivityCreated event: " . $e->getMessage());
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Atividade registrada com sucesso!',
                    'activity' => $activity->load('user')
                ]);
            }

            return back()->with('success', 'Atividade registrada com sucesso!');
        } catch (Throwable $e) {
            Log::error("Error in CrmActivityController@store: " . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Mark the activity as completed.
     */
    public function complete(CrmActivity $activity)
    {
        try {
            $activity->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarefa concluída!',
                    'activity' => $activity
                ]);
            }

            return back()->with('success', 'Tarefa concluída!');
        } catch (Throwable $e) {
            Log::error("Error in CrmActivityController@complete: " . $e->getMessage());
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao concluir: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
}

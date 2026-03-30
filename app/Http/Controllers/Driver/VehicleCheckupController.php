<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\ChecklistItem;
use App\Models\VehicleCheckup;
use App\Models\CheckupResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VehicleCheckupController extends Controller
{
    /**
     * Show the form for creating a new vehicle checkup.
     */
    public function create(): View
    {
        $vehicles = Vehicle::where('status', 'Ativo')->orderBy('placa')->get();
        $checklistItems = ChecklistItem::active()->orderBy('id')->get();

        return view('driver.checkups.create', compact('vehicles', 'checklistItems'));
    }

    /**
     * Store a newly created vehicle checkup.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'notes' => 'nullable|string',
            'responses' => 'required|array|min:1',
            'responses.*.checklist_item_id' => 'required|exists:checklist_items,id',
            'responses.*.is_ok' => 'required|boolean',
            'responses.*.observation' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Determine checkup status based on restrictive items
            $hasRestrictiveFailure = false;
            $checklistItemIds = ChecklistItem::pluck('id', 'id')->toArray();
            
            foreach ($validated['responses'] as $response) {
                $checklistItem = ChecklistItem::find($response['checklist_item_id']);
                
                if ($checklistItem && $checklistItem->is_restrictive && !$response['is_ok']) {
                    $hasRestrictiveFailure = true;
                    break;
                }
            }

            $status = $hasRestrictiveFailure ? 'failed' : 'passed';

            // Create the checkup
            $checkup = VehicleCheckup::create([
                'vehicle_id' => $validated['vehicle_id'],
                'user_id' => auth()->id(),
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create responses
            foreach ($validated['responses'] as $response) {
                CheckupResponse::create([
                    'checkup_id' => $checkup->id,
                    'checklist_item_id' => $response['checklist_item_id'],
                    'is_ok' => $response['is_ok'],
                    'observation' => $response['observation'] ?? null,
                ]);
            }

            DB::commit();

            $message = $status === 'passed' 
                ? 'Check-up realizado com sucesso! Veículo liberado para uso.'
                : 'Check-up registrado. Veículo NÃO liberado - itens restritivos com falha.';

            return redirect()->route('driver.checkups.create')
                ->with($status === 'passed' ? 'success' : 'warning', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao registrar check-up: ' . $e->getMessage());
        }
    }
}

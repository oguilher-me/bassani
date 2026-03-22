<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PipelineStage;
use App\Models\CrmOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PipelineStageController extends Controller
{
    public function index()
    {
        $stages = PipelineStage::withCount('opportunities')->get(); // Global scope handles order
        return view('crm.settings.stages.index', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:20',
            'probability' => 'required|integer|min:0|max:100',
            'required_actions' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug uniqueness manually if needed, or rely on DB unique error, or add check
        if (PipelineStage::where('slug', $validated['slug'])->exists()) {
             $validated['slug'] = $validated['slug'] . '-' . uniqid();
        }

        $validated['is_active'] = true; // Business rule
        $validated['order'] = PipelineStage::max('order') + 1;

        PipelineStage::create($validated);

        return redirect()->route('crm.settings.stages.index')->with('success', 'Etapa criada com sucesso.');
    }

    public function update(Request $request, PipelineStage $stage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:20',
            'probability' => 'required|integer|min:0|max:100',
            'required_actions' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'nullable', // Checkbox handling
        ]);

        // Checkbox logic
        $validated['is_active'] = $request->has('is_active');

        // Do not update slug automatically to preserve links, unless we handle migration
        // Allowing name update without slug update is safer.
        
        $stage->update($validated);

        return redirect()->back()->with('success', 'Etapa atualizada com sucesso.');
    }

    public function destroy(PipelineStage $stage)
    {
        // Validation: Check for existing opportunities
        if ($stage->opportunities()->count() > 0) {
            return redirect()->back()->with('error', 'Esta etapa possui oportunidades vinculadas. Por favor, mova as oportunidades ou inative a etapa.');
        }

        // Protection for system stages if necessary, though simpler is to check count
        if (in_array($stage->slug, ['new', 'won', 'lost'])) {
             // Maybe allow delete if empty? But 'won'/'lost' are crucial for logic.
             return redirect()->back()->with('warning', 'Etapas de sistema (Ganha/Perdida) não devem ser excluídas.');
        }

        $stage->delete();
        return redirect()->back()->with('success', 'Etapa excluída com sucesso.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);

        DB::transaction(function () use ($request) {
            $order = $request->input('order');
            foreach ($order as $index => $id) {
                PipelineStage::where('id', $id)->update(['order' => $index + 1]);
            }
        });

        return response()->json(['success' => true]);
    }
}

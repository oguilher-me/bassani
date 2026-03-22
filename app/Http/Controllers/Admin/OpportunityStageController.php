<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpportunityStage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OpportunityStageController extends Controller
{
    public function index()
    {
        $stages = OpportunityStage::withCount('opportunities')->orderBy('order')->get();
        return view('crm.settings.stages.index', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:crm_opportunity_stages,slug',
            'color' => 'required',
            'probability_default' => 'integer|min:0|max:100',
            'required_fields' => 'array',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = OpportunityStage::max('order') + 1;

        OpportunityStage::create($validated);

        return back()->with('success', 'Etapa criada com sucesso.');
    }

    public function update(Request $request, OpportunityStage $stage)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:crm_opportunity_stages,slug,' . $stage->id,
            'color' => 'required',
            'probability_default' => 'integer|min:0|max:100',
            'required_fields' => 'array',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $stage->update($validated);

        return back()->with('success', 'Etapa atualizada com sucesso.');
    }

    public function destroy(OpportunityStage $stage)
    {
        if ($stage->opportunities()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma etapa com oportunidades vinculadas. Inative-a.');
        }
        
        // System stages protection?
        if (in_array($stage->slug, ['new', 'won', 'lost'])) {
             return back()->with('error', 'Não é possível excluir etapas de sistema.');
        }

        $stage->delete();
        return back()->with('success', 'Etapa excluída.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        
        $order = $request->input('order');
        foreach ($order as $index => $id) {
            OpportunityStage::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmEntity;
use Illuminate\Http\Request;

class CrmEntityController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type') ?? $request->route()->parameter('type');
        
        $query = CrmEntity::query();
        
        if ($type) {
            $query->where('type', $type);
        }

        $entities = $query->get();
        return view('crm.entities.index', compact('entities', 'type'));
    }

    public function show(CrmEntity $entity)
    {
        $entity->load(['opportunities.interactions.user', 'opportunities.proposals']);
        
        // Flatten interactions for the timeline
        $timeline = collect();
        foreach ($entity->opportunities as $opportunity) {
            foreach ($opportunity->interactions as $interaction) {
                $timeline->push([
                    'type' => 'interaction',
                    'data' => $interaction,
                    'date' => $interaction->created_at,
                    'opportunity' => $opportunity->title
                ]);
            }
            foreach ($opportunity->proposals as $proposal) {
                $timeline->push([
                    'type' => 'proposal',
                    'data' => $proposal,
                    'date' => $proposal->created_at,
                    'opportunity' => $opportunity->title
                ]);
            }
        }
        
        $timeline = $timeline->sortByDesc('date');

        return view('crm.entities.show', compact('entity', 'timeline'));
    }

    public function create()
    {
        return view('crm.entities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:lead,client,architect,partner',
            'segment' => 'required|in:residential,commercial,high_end',
            'document' => 'nullable|string',
        ]);

        CrmEntity::create($validated);
        return redirect()->route('crm.entities.index')->with('success', 'Entidade criada com sucesso.');
    }
}

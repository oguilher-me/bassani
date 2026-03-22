<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmOpportunity;
use App\Models\Lead;
use App\Models\CrmEntity;
use App\Models\User;
use App\Models\Architect;
use App\Models\PipelineStage;
use App\Models\OpportunityStage;
use App\Services\Crm\OpportunityService; // Use Service
use Illuminate\Http\Request;

class CrmPipelineController extends Controller
{
    protected $opportunityService;

    public function __construct(OpportunityService $opportunityService)
    {
        $this->opportunityService = $opportunityService;
    }

    public function index(Request $request)
    {
        // Dynamic Stages
        $dbStages = PipelineStage::where('is_active', true)->orderBy('order')->get();
        
        $stages = $dbStages->pluck('name', 'slug')->toArray();
        
        $opportunities = CrmOpportunity::with(['entity', 'customer', 'partner'])
            ->whereIn('status', ['open', 'won', 'lost'])
            ->when($request->user_id, function($q) use ($request) {
                return $q->where('user_id', $request->user_id);
            })
            ->get()
            ->groupBy('stage_id');

        return view('crm.pipeline.index', compact('opportunities', 'stages', 'dbStages'));
    }

    public function create()
    {
        $entities = CrmEntity::whereIn('type', ['client', 'lead'])->get();
        $architects = Architect::all();
        $sellers = \App\Models\Seller::where('status', 'active')->with('user')->get()->pluck('user', 'user_id');
        $users = User::all();
        
        return view('crm.pipeline.create', compact('entities', 'architects', 'users', 'sellers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
             'title' => 'required',
             'entity_id' => 'required',
             'estimated_value' => 'numeric',
             'probability' => 'nullable|integer|min:0|max:100',
             'cpf_cnpj' => 'nullable|string',
             'address' => 'nullable|string',
             'project_size' => 'nullable|string',
             'needs_project_development' => 'boolean',
             'architect_id' => 'nullable|exists:architects,id',
             'project_deadline' => 'nullable|date',
             'expected_closing_date' => 'nullable|date',
             'seller_id' => 'nullable|exists:users,id',
             'owner_id' => 'nullable|exists:users,id',
        ]);
        
        // Ensure boolean handling if checkbox
        $validated['needs_project_development'] = $request->has('needs_project_development');
        
        CrmOpportunity::create($validated + ['user_id' => auth()->id()]);
        
        return redirect()->route('crm.pipeline.index')->with('success', 'Oportunidade criada.');
    }
    
    // API for Drag & Drop
    public function updateStage(Request $request, CrmOpportunity $opportunity)
    {
        $request->validate(['stage_id' => 'required']);
        
        try {
            if ($request->stage_id === 'won') {
                $this->opportunityService->markAsWon($opportunity);
            } elseif ($request->stage_id === 'lost') {
                 // Verify if lost reason provided? For drag & drop, usually we pop a modal.
                 // If dragging to "Lost" column, frontend should handle modal.
                 // Here we assume standard move or specific endpoint for lost.
                 $this->opportunityService->moveStage($opportunity, $request->stage_id);
                 // If lost, we'd need reason.
            } else {
                $this->opportunityService->moveStage($opportunity, $request->stage_id);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show(CrmOpportunity $opportunity)
    {
        $opportunity->load(['interactions.user', 'proposals', 'history.user', 'customer', 'partner', 'architect', 'logs.user', 'activities.user']);
        
        // Fetch active dynamic stages
        $dbStages = PipelineStage::where('is_active', true)->orderBy('order')->get();
        
        return view('crm.pipeline.show', compact('opportunity', 'dbStages'));
    }

    public function edit(CrmOpportunity $opportunity)
    {
        $entities = CrmEntity::whereIn('type', ['client', 'lead'])->get();
        $architects = Architect::all();
        $dbStages = PipelineStage::where('is_active', true)->orderBy('order')->get();
        $sellers = \App\Models\Seller::where('status', 'active')->with('user')->get()->pluck('user', 'user_id');
        $users = User::where('role_id', '!=', 4)->get();
        
        return view('crm.pipeline.edit', compact('opportunity', 'entities', 'architects', 'dbStages', 'users', 'sellers'));
    }

    public function update(Request $request, CrmOpportunity $opportunity)
    {
        try {
            $validated = $request->validate([
                 'title' => 'required|string|max:255',
                 'entity_id' => 'required|exists:crm_entities,id',
                 'architect_id' => 'nullable|exists:architects,id',
                 'estimated_value' => 'required|numeric',
                 'probability' => 'nullable|integer|min:0|max:100',
                 'expected_closing_date' => 'nullable|date',
                 'project_deadline' => 'nullable|date',
                 'address' => 'nullable|string',
                 'project_size' => 'nullable|string',
                 'needs_project_development' => 'nullable', // Handle raw
                 'stage_id' => 'required|exists:crm_pipeline_stages,slug',
                 'seller_id' => 'nullable|exists:users,id',
                 'owner_id' => 'nullable|exists:users,id',
            ]);

            $validated['needs_project_development'] = $request->has('needs_project_development');

            $opportunity->fill($validated);
            $opportunity->save();

            return redirect()->route('crm.opportunities.show', $opportunity->id)
                ->with('success', 'Oportunidade atualizada com sucesso.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar oportunidade: ' . $e->getMessage())->withInput();
        }
    }

    public function markWon(CrmOpportunity $opportunity)
    {
        try {
            $this->opportunityService->markAsWon($opportunity);
            return redirect()->back()->with('success', 'Oportunidade marcada como Ganha!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function markLost(Request $request, CrmOpportunity $opportunity)
    {
        $request->validate(['reason' => 'required']);
        // Append notes to interactions or loss reason?
        // Service expects string reason.
        $this->opportunityService->markAsLost($opportunity, $request->reason . ": " . $request->input('notes'));
        return redirect()->back()->with('success', 'Oportunidade marcada como Perdida.');
    }
}

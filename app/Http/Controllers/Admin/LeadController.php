<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadQualification;
use App\Http\Requests\StoreLeadRequest;
use App\Services\Crm\LeadConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    protected $conversionService;

    public function __construct(LeadConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function index(Request $request)
    {
        $query = Lead::with('user', 'partner', 'qualification')->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(15);

        return view('admin.leads.index', compact('leads'));
    }

    public function create()
    {
        // For modal or separate page. Using separate page for now to be safe, but can be loaded in modal.
        return view('admin.leads.create');
    }

    public function store(StoreLeadRequest $request)
    {
        DB::transaction(function () use ($request) {
            $lead = Lead::create($request->validated() + ['user_id' => auth()->id()]);
            
            // Create empty qualification or logic to populate
            if($request->has('qualification')) {
                 $lead->qualification()->create($request->input('qualification'));
            } else {
                 $lead->qualification()->create([]);
            }
        });

        return redirect()->route('crm.leads.index')->with('success', 'Lead criado com sucesso.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['qualification', 'interactions.user', 'partner', 'opportunities']);
        return view('admin.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $lead->load('qualification');
        return view('admin.leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,converted,lost,discarded',
            'type' => 'required|in:PF,PJ',
            'partner_id' => 'nullable|exists:crm_entities,id',
        ]);

        DB::transaction(function () use ($lead, $validated, $request) {
            $lead->update($validated);
            
            // Update qualification if provided
            if ($request->has('qualification')) {
                if ($lead->qualification) {
                    $lead->qualification->update($request->input('qualification'));
                } else {
                    $lead->qualification()->create($request->input('qualification'));
                }
            }
        });

        return redirect()->route('crm.leads.index')->with('success', 'Lead atualizado com sucesso.');
    }

    public function destroy(Lead $lead)
    {
        // Check if lead has opportunities
        if ($lead->opportunities()->count() > 0) {
            return back()->with('error', 'Não é possível excluir um lead que possui oportunidades vinculadas.');
        }

        DB::transaction(function () use ($lead) {
            // Delete related records
            $lead->qualification()->delete();
            $lead->interactions()->delete();
            $lead->delete();
        });

        return redirect()->route('crm.leads.index')->with('success', 'Lead excluído com sucesso.');
    }


    public function convert(Lead $lead)
    {
        try {
            $result = $this->conversionService->convert($lead);
            return redirect()->route('crm.opportunities.show', $result['opportunity']->id)
                             ->with('success', 'Lead convertido com sucesso em Cliente e Oportunidade.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao converter lead: ' . $e->getMessage());
        }
    }
    
    public function logInteraction(Request $request, Lead $lead)
    {
        $request->validate(['notes' => 'required', 'type' => 'required']);
        
        $lead->logInteraction(
            $request->type, 
            $request->notes, 
            $request->medium ?? 'system'
        );
        
        return back()->with('success', 'Interação registrada.');
    }
}

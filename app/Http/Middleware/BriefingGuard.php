<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CrmOpportunity;

class BriefingGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $opportunity = $request->route('opportunity');
        
        // Only check if we are updating stage to 'development' or similar advanced stage
        // Assuming 'stage_id' determines the stage.
        // And we block if moving *to* development.
        
        if ($request->isMethod('patch') || $request->isMethod('put')) {
            $newStage = $request->input('stage_id');
            
            // Example Rule: Cannot move to 'development' or 'proposal' without briefing
            if ($newStage && in_array($newStage, ['development', 'proposal'])) {
                if ($opportunity && !$opportunity->interactions()->where('type', 'briefing')->exists()) {
                     return back()->withErrors(['stage_id' => 'Não é possível avançar para esta etapa sem um Briefing registrado.']);
                }
            }
        }

        return $next($request);
    }
}

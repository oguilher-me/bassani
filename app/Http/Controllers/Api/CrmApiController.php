<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmOpportunity;
use Illuminate\Http\Request;

class CrmApiController extends Controller
{
    public function updateStage(Request $request, $opportunityId)
    {
        $request->validate([
            'stage_id' => 'required|string',
            'token' => 'required|string', // Simple auth for demo
        ]);

        // Secure this appropriately in production
        if ($request->input('token') !== 'production-secret-token') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $opportunity = CrmOpportunity::find($opportunityId);

        if (!$opportunity) {
            return response()->json(['error' => 'Opportunity not found'], 404);
        }

        $opportunity->update(['stage_id' => $request->input('stage_id')]);

        return response()->json(['message' => 'Stage updated successfully', 'opportunity' => $opportunity]);
    }
}

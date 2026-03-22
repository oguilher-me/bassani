<?php

namespace App\Services\Crm;

use App\Models\CrmOpportunity;
use App\Models\CrmOpportunityLog;
use Illuminate\Support\Facades\Request;

class OpportunityLogService
{
    /**
     * Log an action for an opportunity.
     *
     * @param CrmOpportunity $opportunity
     * @param string $action
     * @param array $before
     * @param array $after
     * @param string|null $description
     * @param int|null $duration_seconds
     * @return CrmOpportunityLog
     */
    public static function log(CrmOpportunity $opportunity, string $action, array $before = [], array $after = [], ?string $description = null, ?int $duration_seconds = null)
    {
        return CrmOpportunityLog::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'before' => !empty($before) ? $before : null,
            'after' => !empty($after) ? $after : null,
            'description' => $description,
            'duration_seconds' => $duration_seconds,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}

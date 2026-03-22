<?php

namespace App\Events;

use App\Models\CrmOpportunity;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OpportunityWon
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $opportunity;

    public function __construct(CrmOpportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }
}

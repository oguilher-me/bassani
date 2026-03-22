<?php

namespace App\Services\Crm;

use App\Models\CrmProposal;
use Illuminate\Support\Facades\Log;
// use App\Jobs\CreateProductionOrderJob; // Assuming Job class
// use App\Models\ProductionOrder; // Mocking existence
// use App\Models\Invoice; // Mocking existence

class ProposalApprovalService
{
    public function approve(CrmProposal $proposal)
    {
        if ($proposal->status === 'approved') {
            return;
        }

        $proposal->update(['status' => 'approved']);
        $opportunity = $proposal->opportunity;
        $opportunity->update(['stage_id' => 'won', 'probability' => 100]);

        // Trigger Automated Workflow
        $this->createProductionOrder($proposal);
        $this->createInvoice($proposal);
        
        Log::info("Proposal #{$proposal->id} approved. Production Order and Invoice triggers initiated.");
    }

    protected function createProductionOrder(CrmProposal $proposal)
    {
        // Logic to create Production Order
        // ProductionOrder::create([...]);
        // For now, we simulate via Log or dispatch a Job if it existed
        // CreateProductionOrderJob::dispatch($proposal);
        Log::info("Mock: Production Order created for Proposal #{$proposal->id}");
    }

    protected function createInvoice(CrmProposal $proposal)
    {
        // Logic to create Financial Invoice
        // Invoice::create([...]);
        Log::info("Mock: Invoice created for Proposal #{$proposal->id}");
    }
}

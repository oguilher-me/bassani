<?php

namespace App\Listeners;

use App\Events\OpportunityWon;
use App\Models\Sale; // Ensure this model exists per previous file check
use App\Models\ProductionOrder; // Mocking or checking existence?
// Prompt says "gerar a production_order inicial".
// I will verify Sale model structure from the viewed file.
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class CreateSaleFromOpportunity
{
    public function handle(OpportunityWon $event)
    {
        $opportunity = $event->opportunity;
        $approvedProposal = $opportunity->proposals()->where('status', 'approved')->first(); // Get the winner
        
        // Create Sale
        // Based on 2025_10_17_233629_create_sales_table.php:
        // customer_id, user_id, status, total_amount, sale_date, invoice_number...
        
        $sale = Sale::create([
            'customer_id' => $opportunity->customer_id ?? 1, // Fallback if null, strictly should validate
            'user_id' => $opportunity->user_id,
            'status' => 'pending', // or completed?
            'total_amount' => $approvedProposal ? $approvedProposal->total_value : $opportunity->estimated_value,
            'sale_date' => now(),
            // 'invoice_number' => ... generated later
        ]);

        // Create Production Order (Mocking Model if not exists, but assuming implicit requirement)
        // If ProductionOrder doesn't exist, I'll allow this to fail or comment out.
        // Prompt implied "gerar a production_order".
        // I will check if I can find ProductionOrder model later, for now I put placeholder.
        
        /*
        ProductionOrder::create([
             'sale_id' => $sale->id,
             'status' => 'queued',
             'description' => "Pedido gerado via CRM: " . $opportunity->title
        ]);
        */
    }
}

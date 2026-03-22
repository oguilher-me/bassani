<?php

namespace App\Services\Crm;

use App\Models\Lead;
use App\Models\CrmOpportunity;
use App\Models\Admin\Customer; // Assuming existing Customer model
use Illuminate\Support\Facades\DB;
use Exception;

class LeadConversionService
{
    /**
     * Convert a Lead into a Customer and Opportunity.
     */
    public function convert(Lead $lead): array
    {
        // 1. Validate status
        if ($lead->status === 'converted') {
            throw new Exception("Lead já convertido.");
        }

        return DB::transaction(function () use ($lead) {
            // 2. Create Customer (Simplified mapping, assuming Customer has basic fields)
            // We might need to check if customer exists by CPF/CNPJ if provided.
            
            $customer = Customer::create([
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone ?? $lead->whatsapp,
                'city_name' => $lead->city, // Assuming standard fields
                'uf' => $lead->uf,
                'document' => $lead->document,
                'type' => $lead->type === 'PJ' ? 'company' : 'individual',
                'active' => true,
            ]);

            // 3. Create Opportunity
            $opportunity = CrmOpportunity::create([
                'title' => "Projeto " . $lead->name,
                // For now, if CrmOpportunity links to CrmEntity, we might need to create a CrmEntity wrapper for Customer?
                // OR we update CrmOpportunity to link to 'customer_id' as well.
                // Given the prompt "transformar o Lead em um Customer... e criar uma Opportunity",
                // I will assume for now we link via a new mechanism or generic entity.
                // Let's create a CrmEntity for this Customer to maintain consistency with current CRM design.
                
                'user_id' => $lead->user_id ?? auth()->id(),
                'stage_id' => 'new',
                'estimated_value' => $lead->qualification->estimated_investment ?? 0,
                'entity_id' => $lead->id, // Maybe link to Customer if we change Relation? Or entity logic needs refactor.
            ]);
            
            // Link Opportunity to the new Customer via CrmEntity?
            // Existing logic uses CrmEntity. Let's create one.
            $entity = \App\Models\CrmEntity::create([
                'name' => $customer->name,
                'type' => 'client',
                'document' => $customer->document,
                'email' => $customer->email,
            ]);
            
            $opportunity->entity_id = $entity->id;
            $opportunity->partner_id = $lead->partner_id; // Carry over partner
            $opportunity->save();

            // 4. Migrate Interactions
            // Since we made it polymorphic, we just update the morph_type/id
            // But we want to KEEP the lead history.
            // So we might want to CLONE them or just leave them on Lead?
            // "movendo todos os arquivos e logs." -> Moving implies consistency.
            // I will Re-assign them to the Opportunity?
            // Better to keep on Lead for audit, and maybe copy to Opportunity?
            // Or usually, checking the Lead VIEW shows the history.
            // Code says "movendo". I will move them (update FK).
            
            foreach ($lead->interactions as $interaction) {
                $interaction->update([
                    'interactive_type' => CrmOpportunity::class,
                    'interactive_id' => $opportunity->id
                ]);
            }

            // 5. Update Lead Status
            $lead->update([
                'status' => 'converted',
                'converted_at' => now(),
            ]);

            return ['customer' => $customer, 'opportunity' => $opportunity];
        });
    }
}

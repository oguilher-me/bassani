<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class LeadConversionService
{
    /**
     * Convert a lead to a customer.
     *
     * @param Lead $lead
     * @return Customer
     */
    public function convert(Lead $lead): Customer
    {
        return DB::transaction(function () use ($lead) {
            // Check if lead already has a customer
            if ($lead->customer_id) {
                $customer = Customer::find($lead->customer_id);
                if ($customer) {
                    return $customer;
                }
            }

            // Check if a customer with the same email exists
            if ($lead->email) {
                $existingCustomer = Customer::where('email', $lead->email)->first();
                if ($existingCustomer) {
                    $lead->update(['customer_id' => $existingCustomer->id, 'status' => 'converted']);
                    return $existingCustomer;
                }
            }

            // Create new customer
            $customer = Customer::create([
                'customer_type' => 'individual', // Default to individual, or add logic to determine
                'full_name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'status' => 'ACTIVE', // Assuming 'ACTIVE' is a valid status
            ]);

            $lead->update([
                'customer_id' => $customer->id,
                'status' => 'converted'
            ]);

            // Move interactions? Optional, but good practice.
            // For now, interactions are linked to lead_id, but the table also has customer_id.
            $lead->interactions()->update(['customer_id' => $customer->id]);
            $lead->tasks()->update(['customer_id' => $customer->id]);

            return $customer;
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\DeliveryStatusEnum;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'issue_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'expected_delivery_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'representative_id' => \App\Models\Representative::factory(),
            'sales_responsible' => $this->faker->name(),
            'sales_division' => $this->faker->randomElement(['Retail', 'Wholesale', 'Corporate', 'Export']),
            'carrier_id' => \App\Models\Carrier::factory(),
            'payment_term_id' => \App\Models\PaymentTerm::factory(),
            'currency' => $this->faker->currencyCode(),
            'contact_name' => $this->faker->name(),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->unique()->safeEmail(),
            'purchase_order' => $this->faker->optional()->bothify('PO-########'),
            'erp_code' => $this->faker->unique()->randomNumber(8),
            'notes' => $this->faker->sentence(),
            'total_items' => $this->faker->randomFloat(2, 100, 10000),
            'total_discounts' => $this->faker->randomFloat(2, 0, 1000),
            'total_ipi' => $this->faker->randomFloat(2, 0, 500),
            'total_icms_st' => $this->faker->randomFloat(2, 0, 500),
            'shipping_cost' => $this->faker->randomFloat(2, 0, 200),
            'grand_total' => $this->faker->randomFloat(2, 100, 10000),
            'total_weight' => $this->faker->randomFloat(2, 1, 1000),
            'total_volume' => $this->faker->randomFloat(2, 0.1, 100),
            'total_packages' => $this->faker->numberBetween(1, 50),
            'order_status' => $this->faker->randomElement(['Open', 'Partially Invoiced', 'Invoiced', 'Cancelled']),
            'delivery_status' => $this->faker->randomElement(collect(DeliveryStatusEnum::cases())->map(fn ($enum) => $enum->value)->toArray()),
            'payment_method' => $this->faker->randomElement(['Credit Card', 'Debit Card', 'Bank Transfer', 'Pix', 'Cash']),
            'shipping_method' => $this->faker->randomElement(['Próprio', 'Terceirizado']),
            'tracking_code' => $this->faker->unique()->uuid(),
        ];
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlannedShipment>
 */
class PlannedShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plannedDate = $this->faker->dateTimeBetween('now', '+1 year');
        $deliveryDate = (clone $plannedDate)->modify('+ ' . $this->faker->numberBetween(1, 10) . ' days');

        return [
            'shipment_number' => 'PS-' . $this->faker->unique()->randomNumber(5),
            'status' => $this->faker->randomElement(['Planned', 'In Transit', 'Delivered', 'Returned', 'Cancelled']),
            'planned_departure_date' => $plannedDate,
            'actual_departure_date' => $this->faker->optional()->dateTimeBetween($plannedDate, (clone $plannedDate)->modify('+5 days')),
            'planned_delivery_date' => $deliveryDate,
            'actual_delivery_date' => $this->faker->optional()->dateTimeBetween($deliveryDate, (clone $deliveryDate)->modify('+5 days')),
            'total_weight' => $this->faker->randomFloat(2, 100, 5000),
            'total_volume' => $this->faker->randomFloat(2, 1, 100),
            'total_sales' => $this->faker->numberBetween(1, 10),
            'total_invoices' => $this->faker->numberBetween(1, 10),
            'delivery_window_start' => (clone $plannedDate)->setTime($this->faker->numberBetween(8, 12), 0),
            'delivery_window_end' => (clone $plannedDate)->setTime($this->faker->numberBetween(13, 18), 0),
            'destination_address' => $this->faker->address,
            'remarks' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

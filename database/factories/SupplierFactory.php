<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'document_number' => $this->faker->unique()->numerify('##############'),
            'supplier_type' => $this->faker->randomElement(['Pessoa Física', 'Pessoa Jurídica']),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'contact_person' => $this->faker->name,
            'address' => $this->faker->streetName,
            'address_number' => $this->faker->buildingNumber,
            'neighborhood' => $this->faker->cityPrefix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zip_code' => $this->faker->postcode,
            'services_offered' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'documents' => json_encode([$this->faker->url, $this->faker->url]),
        ];
    }
}

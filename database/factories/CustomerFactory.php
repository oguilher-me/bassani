<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $customerType = $this->faker->randomElement(['individual', 'company']);

        $data = [
            'customer_type' => $customerType,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address_street' => $this->faker->streetName,
            'address_number' => $this->faker->buildingNumber,
            'address_neighborhood' => $this->faker->cityPrefix,
            'address_city' => $this->faker->city,
            'address_state' => $this->faker->stateAbbr,
            'address_zip_code' => $this->faker->postcode,
            'address_type' => $this->faker->randomElement(['residential', 'commercial']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];

        if ($customerType === 'individual') {
            $data['full_name'] = $this->faker->name;
            $data['cpf'] = $this->faker->unique()->numerify('###########');
            $data['rg'] = $this->faker->unique()->numerify('#########');
            $data['company_name'] = null;
            $data['cnpj'] = null;
            $data['ie'] = null;
        } else {
            $data['company_name'] = $this->faker->company;
            $data['cnpj'] = $this->faker->unique()->numerify('##############');
            $data['ie'] = $this->faker->unique()->numerify('###########');
            $data['full_name'] = null;
            $data['cpf'] = null;
            $data['rg'] = null;
        }

        return $data;
    }
}
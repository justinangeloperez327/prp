<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'fax' => $this->faker->optional()->phoneNumber,
            'website' => $this->faker->optional()->url,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'street' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->randomElement(['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA']),
            'postcode' => $this->faker->postcode,
            'country' => 'Australia',
            'apply_delivery_charge' => $this->faker->randomElement(['none', 'fixed', 'minimum-order']),
            'delivery_charge' => $this->faker->randomFloat(2, 0, 100),
            'charge_trigger' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}

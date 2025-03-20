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
            'company' => $this->faker->company,
            'phone' => $this->faker->numerify('+61 #### ####'),
            'email' => $this->faker->unique()->safeEmail,
            'fax' => $this->faker->numerify('+61 #### ####'),
            'website' => $this->faker->optional()->url,
            'status' => 'active',
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

    /**
     * Indicate that the customer has orders.
     */
    public function hasOrdersWithItems(int $orderCount = 5, int $itemCount = 3): Factory
    {
        return $this->has(
            \App\Models\Order::factory()
                ->count($orderCount)
                ->withItems($itemCount),
            'orders'
        );
    }
}

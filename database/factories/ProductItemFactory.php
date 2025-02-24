<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductItem>
 */
class ProductItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'size' => $this->faker->word(),
            'unit' => $this->faker->word(),
            'quantity' => $this->faker->randomNumber(),
            'gsm' => $this->faker->randomNumber(),
            'sheets_per_mill_pack' => $this->faker->randomNumber(),
            'sheets_per_pallet' => $this->faker->randomNumber(),
            'price_per_quantity' => $this->faker->randomFloat(2, 1, 1000),
            'price_broken_mill_pack' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}

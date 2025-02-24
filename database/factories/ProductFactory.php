<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'type_list' => $this->faker->words(3, true),
            'colour_list' => $this->faker->words(3, true),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'product_category_id' => \App\Models\ProductCategory::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'product_item_id' => \App\Models\ProductItem::factory(),
            'product_colour' => $this->faker->colorName(),
            'product_size' => $this->faker->randomElement(['small', 'medium', 'large']),
            'quantity' => $this->faker->numberBetween(1, 10),
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'special_instructions' => $this->faker->text(),
        ];
    }
}

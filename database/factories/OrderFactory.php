<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'order_date' => $this->faker->date(),
            'order_time' => $this->faker->time(),
            'would_like_it_by' => $this->faker->date(),
            // 'due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['draft', 'new', 'processing', 'on-hold', 'cancelled', 'overdue']),
            'additional_instructions' => $this->faker->optional()->text(),
            'delivery_charge' => $this->faker->randomFloat(2, 0, 1000),
            'grand_total' => $this->faker->randomFloat(2, 0, 1000),
            'purchase_order_no' => $this->faker->unique()->randomNumber(8),
        ];
    }

    public function withItems(int $count = 3): Factory
    {
        return $this->has(\App\Models\OrderItem::factory()->count($count), 'items');
    }
}

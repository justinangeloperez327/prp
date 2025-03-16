<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductItem;
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

        $productCategory = ProductCategory::inRandomOrder()->first();

        $product = Product::where('product_category_id', $productCategory->id)
            ->inRandomOrder()
            ->first();

        $productItem = ProductItem::where('product_id', $product->id)
            ->inRandomOrder()
            ->first();

        $colour = $product->colour_list ? $this->faker->randomElement(explode(',', $product->colour_list)) : null;
        $quantity = $this->faker->numberBetween(1, 10);

        $total = (!empty($productItem->price_per_quantity) ? $productItem->price_per_quantity : 0) * $quantity;

        return [
            'order_id' => \App\Models\Order::factory(),
            'product_item_id' => $productItem->id,
            'product_category_id' => $productCategory->id,
            'product_id' => $product->id,
            'product_colour' => $colour,
            'product_size' => $productItem->size,
            'quantity' => $quantity,
            'total' => $total,
        ];
    }
}

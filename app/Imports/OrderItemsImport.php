<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrderItemsImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $order = Order::where('order_no', $row['orderno'])->first();
            if ($order) {
                $product = Product::where('product_uid', $row['productunid'])->first();
                $productCategory = ProductCategory::where('category_uid', $row['productcategoryunid'])->first();
                $productItem = ProductItem::where('product_item_uid', $row['productitemunid'])->first();

                if ($product && $productCategory && $productItem) {
                    DB::table('order_items')->updateOrInsert([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_category_id' => $productCategory->id,
                        'product_item_id' => $productItem->id,
                    ], [
                        'product_size' => $row['productitemsize'],
                        'product_colour' => $row['productcolourlist'],
                        'quantity' => $row['qty'] ? $row['qty'] : 0,
                        'total' => $row['totalexgst'],
                        'special_instructions' => $row['notes'],
                    ]);
                }
            }
        }
    }
}

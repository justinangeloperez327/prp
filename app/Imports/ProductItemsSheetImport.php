<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductItemsSheetImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow
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
            $product = Product::where('name', $row['product'])->first();

            if ($product) {
                DB::table('product_items')->updateOrInsert([
                    'product_item_uid' => $row['productitemunid'],
                ], [
                    'product_id' => $product->id,
                    'size' => $row['product_item_size'],
                    'gsm' => $row['gsm'],
                    'type' => $row['product_type'],
                    'unit' => $row['item_unit'],
                    'quantity' => $row['item_qty'],
                    'sheets_per_mill_pack' => $row['sheets_per_mill_pack'],
                    'sheets_per_pallet' => $row['sheets_per_pallet'],
                    'price_per_quantity' => $row['price_per_qty'],
                    'price_broken_mill_pack' => $row['price_broken_mill_pack'],
                    'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                ]);
            }
        }
    }
}

<?php

namespace App\Imports;

use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsSheetImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow
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
            $category = ProductCategory::where('category_uid', $row['categoryunid'])->first();

            if ($category) {
                DB::table('products')->updateOrInsert([
                    'product_uid' => $row['productunid'],
                ], [
                    'product_category_id' => $category->id,
                    'name' => $row['product_name'],
                    'description' => $row['product_description'],
                    'type_list' => $row['product_type_list'],
                    'colour_list' => $row['product_colour_list'],
                    'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                ]);
            }
        }
    }
}

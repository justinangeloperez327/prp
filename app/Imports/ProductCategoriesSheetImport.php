<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductCategoriesSheetImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow
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
            DB::table('product_categories')->updateOrInsert([
                'category_uid' => $row['categoryunid'],
            ], [
                'name' => $row['category_name'],
                'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                'order' => $row['sort_order'],
            ]);
        }
    }
}

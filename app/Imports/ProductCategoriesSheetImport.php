<?php

namespace App\Imports;

use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductCategoriesSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row) {
            $productCategory = ProductCategory::firstOrCreate([
                'category_uid' => $row['categoryunid'],
            ], [
                'name' => $row['category_name'],
                'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                'order' => $row['sort_order'],
            ]);
            $productCategory->save();
        }
    }
}

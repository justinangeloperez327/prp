<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiscountsSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $customer = Customer::where('customer_code', $row['customercode'])->first();
            $product = Product::where('product_uid', $row['productunid'])->first();

            if ($row['discountunid'] && $customer && $product) {
                Discount::firstOrCreate([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'discount_unid' => $row['discountunid'],
                ], [
                    'discount' => $row['discount'],
                    'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                ]);
            }
        }
    }
}

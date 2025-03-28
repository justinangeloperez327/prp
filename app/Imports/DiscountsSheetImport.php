<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiscountsSheetImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
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
            $customer = Customer::where('customer_code', $row['customercode'])->first();
            $product = Product::where('product_uid', $row['productunid'])->first();

            if ($row['discountunid'] && $customer && $product) {
                DB::table('discounts')->updateOrInsert([
                    'discount_unid' => $row['discountunid'],
                ], [
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'discount' => $row['discount'],
                    'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                ]);
            }
        }
    }
}

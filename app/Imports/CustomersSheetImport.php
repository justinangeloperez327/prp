<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersSheetImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow
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
            if ($row['customercode']) {
                DB::table('customers')->updateOrInsert([
                    'customer_code' => $row['customercode'],
                ], [
                    'company' => $row['company'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'fax' => $row['fax'],
                    'website' => $row['website'],
                    'street' => $row['streetaddress'],
                    'city' => $row['suburb'],
                    'state' => $row['state'],
                    'postcode' => $row['postcode'],
                    'country' => $row['country'] ?? 'Australia',
                    'apply_delivery_charge' => Str::slug($row['applydeliverycharge']),
                    'delivery_charge' => $row['deliverycharge'] ?? 0.00,
                    'charge_trigger' => $row['chargetrigger'] ?? 0.00,
                    'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                ]);
            }
        }
    }
}

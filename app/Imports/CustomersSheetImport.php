<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if ($row['customercode']) {
                Customer::firstOrCreate([
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

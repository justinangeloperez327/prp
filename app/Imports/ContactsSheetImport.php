<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $customer = Customer::where('customer_code', $row['customercode'])->first();

            if ($customer && $row['active'] === 'Yes' && $row['contactcode']) {
                $user = User::firstOrCreate([
                    'username' => $row['username'],
                ], [
                    'password' => bcrypt('password'),
                ]);

                $user->assignRole('customer');

                Contact::firstOrCreate([
                    'contact_code' => $row['contactcode'],
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                ], [
                    'title' => $row['title'] ==='Select a title' ? null : $row['title'],
                    'first_name' => $row['firstname'],
                    'last_name' => $row['lastname'],
                    'direct_phone' => $row['phone'],
                    'mobile_phone' => $row['mobilenumber'],
                    'email' => $row['email'],
                    'status' => $row['active'] === 'Yes' ? 'active' : 'inactive',
                ]);
            }
        }
    }
}

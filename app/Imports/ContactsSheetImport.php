<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsSheetImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
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
        $password = Hash::make('password');
        foreach ($collection as $row) {
            $customer = Customer::where('customer_code', $row['customercode'])->first();

            if ($customer && $row['active'] === 'Yes' && $row['contactcode']) {
                $email = $row['email'];

                $email = $this->generateUniqueEmail($email);

                $user = User::create([
                    'email' => $email,
                    'password' => $password,
                ]);

                $user->assignRole('customer');

                DB::table('contacts')->updateOrInsert([
                    'contact_code' => $row['contactcode'],
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                ], [
                    'title' => $row['title'] === 'Select a title' ? null : $row['title'],
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

    private function appendSuffixToEmail($email, $counter)
    {
        $emailParts = explode('@', $email);
        $emailParts[0] .= '_dup'.$counter;

        return implode('@', $emailParts);
    }

    private function generateUniqueEmail($email)
    {
        $originalEmail = $email;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = $this->appendSuffixToEmail($originalEmail, $counter);
            $counter++;
        }

        return $email;
    }
}

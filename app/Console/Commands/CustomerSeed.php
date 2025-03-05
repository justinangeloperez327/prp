<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test customer user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('name', 'test')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => Hash::make('test'),
                'remember_token' => Str::random(10),
            ]);
        }
        $customer = Customer::where('company_name', 'Test Company')->first();

        if(!$customer) {
            $customer = Customer::create([
                'company_name' => 'Test Company',
                'customer_no' => '123456',
                'phone' => '1234567890',
                'email' => 'test@gmail.com',
                'fax' => '1234567890',
                'website' => 'test.com',
                'status' => 'active',
                'street' => '123 Test St',
                'city' => 'Test City',
                'state' => 'VIC',
                'postcode' => '12345',
                'apply_delivery_charge' => 'none',
                'delivery_charge' => 15,
                'charge_trigger' => 0,
                'notes' => 'Test notes',
            ]);
        }

        $user->assignRole('customer');

        $contact = Contact::where('user_id', $user->id)->first();
        if(!$contact) {
            $contact = Contact::create([
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'first_name' => 'Test',
                'last_name' => 'User',
                'title' => 'Mr',
                'direct_phone' => '1234567890',
                'mobile_phone' => '1234567890',
                'notes' => 'Test notes',
                'status' => 'active',
            ]);
        }

        $this->info('Customer user created successfully.');
    }
}

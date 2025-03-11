<?php

namespace App\Filament\App\Resources\OrderResource\Pages;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\OrderResource;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->hasRole('customer')) {
            $contact = Contact::where('user_id', Auth::id())->first();
            $customer = Customer::where('id', $contact->customer_id)->first();
            $data['customer_id'] = $customer->id;
        }

        $data['status'] = 'new';

        return $data;
    }
}

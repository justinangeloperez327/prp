<?php

namespace App\Filament\Admin\Resources\CustomerResource\Pages;

use App\Filament\Admin\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['delivery_charge'] = $data['apply_delivery_charge'] == 'none' ? 0 : $data['delivery_charge'];
        $data['charge_trigger'] = $data['apply_delivery_charge'] == 'none' ? 0 : $data['charge_trigger'];

        return $data;
    }

    protected function afterCreate(): void
    {
        $customer = $this->record;
        // last customer codre is C00234 with id 234;
        $customerCode = 'C'.str_pad($customer->id, 5, '0', STR_PAD_LEFT);

        $customer->update([
            'customer_code' => $customerCode,
        ]);
    }
}

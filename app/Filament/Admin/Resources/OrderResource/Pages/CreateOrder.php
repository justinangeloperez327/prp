<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Events\OrderCreated;
use App\Filament\Admin\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->hasRole('customer')) {
            $data['customer_id'] = Auth::user()->contact->customer_id;
        }

        $data['status'] = 'new';

        return $data;
    }

    protected function afterCreate(): void
    {
        $order = $this->record;

        // OrderCreated::dispatch($order);
    }
}

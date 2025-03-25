<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Events\OrderCreated;
use App\Filament\Admin\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'new';

        return $data;
    }

    protected function afterCreate(): void
    {
        $order = $this->record;

        OrderCreated::dispatch($order);
    }
}

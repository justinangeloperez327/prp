<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['customer_id'] = $this->record->id;

        return $data;
    }
}

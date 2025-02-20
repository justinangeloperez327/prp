<?php

namespace App\Filament\Resources\CustomerContactResource\Pages;

use App\Filament\Resources\CustomerContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerContact extends EditRecord
{
    protected static string $resource = CustomerContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

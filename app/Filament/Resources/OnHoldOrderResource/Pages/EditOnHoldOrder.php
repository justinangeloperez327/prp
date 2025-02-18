<?php

namespace App\Filament\Resources\OnHoldOrderResource\Pages;

use App\Filament\Resources\OnHoldOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOnHoldOrder extends EditRecord
{
    protected static string $resource = OnHoldOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

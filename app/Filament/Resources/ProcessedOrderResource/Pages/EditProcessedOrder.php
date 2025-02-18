<?php

namespace App\Filament\Resources\ProcessedOrderResource\Pages;

use App\Filament\Resources\ProcessedOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessedOrder extends EditRecord
{
    protected static string $resource = ProcessedOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

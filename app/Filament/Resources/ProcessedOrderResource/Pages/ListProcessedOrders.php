<?php

namespace App\Filament\Resources\ProcessedOrderResource\Pages;

use App\Filament\Resources\ProcessedOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessedOrders extends ListRecords
{
    protected static string $resource = ProcessedOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

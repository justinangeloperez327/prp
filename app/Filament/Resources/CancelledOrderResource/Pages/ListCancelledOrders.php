<?php

namespace App\Filament\Resources\CancelledOrderResource\Pages;

use App\Filament\Resources\CancelledOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCancelledOrders extends ListRecords
{
    protected static string $resource = CancelledOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

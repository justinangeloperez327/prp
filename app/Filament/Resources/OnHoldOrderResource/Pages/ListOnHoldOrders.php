<?php

namespace App\Filament\Resources\OnHoldOrderResource\Pages;

use App\Filament\Resources\OnHoldOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnHoldOrders extends ListRecords
{
    protected static string $resource = OnHoldOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

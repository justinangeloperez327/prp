<?php

namespace App\Filament\Resources\OverdueOrderResource\Pages;

use App\Filament\Resources\OverdueOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOverdueOrders extends ListRecords
{
    protected static string $resource = OverdueOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

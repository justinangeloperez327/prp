<?php

namespace App\Filament\App\Resources\ProductItemResource\Pages;

use App\Filament\App\Resources\ProductItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductItems extends ListRecords
{
    protected static string $resource = ProductItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Item'),
        ];
    }
}

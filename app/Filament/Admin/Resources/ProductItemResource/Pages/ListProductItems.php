<?php

namespace App\Filament\Admin\Resources\ProductItemResource\Pages;

use App\Filament\Admin\Resources\ProductItemResource;
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

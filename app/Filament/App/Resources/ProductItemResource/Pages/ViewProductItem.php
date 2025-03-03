<?php

namespace App\Filament\App\Resources\ProductItemResource\Pages;

use App\Filament\App\Resources\ProductItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductItem extends ViewRecord
{
    protected static string $resource = ProductItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

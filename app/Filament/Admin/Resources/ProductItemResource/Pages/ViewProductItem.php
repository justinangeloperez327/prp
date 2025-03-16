<?php

namespace App\Filament\Admin\Resources\ProductItemResource\Pages;

use App\Filament\Admin\Resources\ProductItemResource;
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

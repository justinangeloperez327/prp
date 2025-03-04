<?php

namespace App\Filament\App\Resources\ProductItemResource\Pages;

use App\Filament\App\Resources\ProductItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductItem extends EditRecord
{
    protected static string $resource = ProductItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }
}

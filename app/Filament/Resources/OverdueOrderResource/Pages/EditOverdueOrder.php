<?php

namespace App\Filament\Resources\OverdueOrderResource\Pages;

use App\Filament\Resources\OverdueOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOverdueOrder extends EditRecord
{
    protected static string $resource = OverdueOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

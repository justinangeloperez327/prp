<?php

namespace App\Filament\Resources\CancelledOrderResource\Pages;

use App\Filament\Resources\CancelledOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCancelledOrder extends CreateRecord
{
    protected static string $resource = CancelledOrderResource::class;
}

<?php

namespace App\Filament\App\Resources\ProductItemResource\Pages;

use App\Filament\App\Resources\ProductItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductItem extends CreateRecord
{
    protected static string $resource = ProductItemResource::class;
}

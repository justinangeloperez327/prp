<?php

namespace App\Filament\Admin\Resources\ProductItemResource\Pages;

use App\Filament\Admin\Resources\ProductItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductItem extends CreateRecord
{
    protected static string $resource = ProductItemResource::class;
}

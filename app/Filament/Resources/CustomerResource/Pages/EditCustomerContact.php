<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditCustomerContact extends EditRecord
{
    use NestedPage;

    protected static string $relationship = 'contacts';

    protected static string $resource = CustomerResource::class;
}

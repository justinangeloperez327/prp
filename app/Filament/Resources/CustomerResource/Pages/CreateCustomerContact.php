<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Pages\CreateRelatedRecord;

class CreateCustomerContact extends CreateRelatedRecord
{
    use NestedPage;

    protected static string $relationship = 'contacts';

    protected static string $resource = CustomerResource::class;
}

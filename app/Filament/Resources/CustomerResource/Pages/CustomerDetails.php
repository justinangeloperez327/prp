<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class CustomerDetails extends Page
{
    use InteractsWithRecord;
    protected static string $resource = CustomerResource::class;

    protected static string $view = 'filament.resources.customer-resource.pages.customer-details';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}

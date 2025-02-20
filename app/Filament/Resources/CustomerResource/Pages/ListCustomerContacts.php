<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Contact;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Illuminate\Database\Eloquent\Builder;

class ListCustomerContacts extends ListRecords
{
    use NestedPage;

    protected static string $relationship = 'contacts';

    protected static string $resource = CustomerResource::class;

    protected static string $view = 'filament.resources.customer-resource.pages.list-customer-contacts';

    // public function getTableQuery(): Builder
    // {
    //     dd($)

    //     return Contact::query()->where('customer_id', $this->record->id);
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Contact'),
        ];
    }
}

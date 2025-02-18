<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Customer Information')
                ->tabs([
                    // Tab 1: Customer Details
                    Tab::make('Customer Details')
                        ->schema([

                            // Add additional customer fields as needed…
                        ]),

                    // Tab 2: Contact List (a relation)
                    Tab::make('Contact List')
                        ->schema([
                            // You can include a custom table component here or embed a relation manager.
                            // For example, if you have a relation manager for contacts, you could use:
                            // \App\Filament\Resources\CustomerResource\RelationManagers\ContactsRelationManager::getTableSchema(),
                        ]),

                    // Tab 3: Discount List (a relation)
                    Tab::make('Discount List')
                        ->schema([
                            // Add your discount list table or relation manager component here.
                        ]),

                    // Tab 4: Consignment List (a relation)
                    Tab::make('Consignment List')
                        ->schema([
                            // Add your consignment list component here.
                        ]),

                    // Tab 5: Notes (a relation)
                    Tab::make('Notes')
                        ->schema([
                            // Add your notes display or relation manager component here.
                        ]),
                ]),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\Tabs as ComponentTabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $navigationGroup = 'Customers';
    protected static ?string $navigationLabel = 'Customer List';

    protected static ?string $model = Customer::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Details')
                    ->columns([
                        'md' => 4,
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->columnSpan(2)
                            ->required(),
                        TextInput::make('customer_no')
                            ->label('Customer No')
                            ->columnSpan(1)
                            ->required()
                            ->placeholder('Auto-generated')
                            ->disabledOn('create'),
                        Radio::make('status')
                            ->label('Status')
                            ->columnSpan(1)
                            ->default('inactive')
                            ->options([
                                'active' => 'Yes',
                                'inactive' => 'No',
                            ]),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->prefixIcon('heroicon-s-phone')
                            ->columnSpan(2)
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->prefixIcon('heroicon-s-envelope')
                            ->columnSpan(2)
                            ->required(),
                        TextInput::make('fax')
                            ->label('Fax Number')
                            ->prefixIcon('heroicon-s-printer')
                            ->columnSpan(2)
                            ->required(),
                        TextInput::make('website')
                            ->label('Website Address')
                            ->prefixIcon('heroicon-s-globe-alt')
                            ->columnSpan(2)
                            ->required(),

                    ]),
                Section::make('Address')
                    ->columns([
                        'md' => 2,
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('street')
                            ->label('Street')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('city')
                            ->label('City')
                            ->required(),
                        Select::make('state')
                            ->label('State')
                            ->placeholder('Select a state')
                            ->default('VIC')
                            ->options([
                                'ACT' => 'ACT',
                                'NSW' => 'NSW',
                                'NT' => 'NT',
                                'QLD' => 'QLD',
                                'SA' => 'SA',
                                'TAS' => 'TAS',
                                'VIC' => 'VIC',
                                'WA' => 'WA',
                            ])
                            ->required(),
                        TextInput::make('postcode')
                            ->label('Postcode')
                            ->required(),
                    ]),
                Section::make('Delivery Details')
                    ->columns([
                        'md' => 4,
                        'sm' => 1
                    ])
                    ->schema([
                        Select::make('apply_delivery_charge')
                            ->label('Apply Delivery Charge')
                            ->columnSpan(2)
                            ->options([
                                'none' => 'None',
                                'fixed' => 'Fixed',
                                'minimum-order' => 'Minimum Order',
                            ])
                            ->default('none'),
                        TextInput::make('delivery_charge')
                            ->label('Delivery Charge')
                            ->prefixIcon('heroicon-s-currency-dollar'),
                        TextInput::make('charge_trigger')
                            ->label('Charge Trigger')
                            ->prefixIcon('heroicon-s-currency-dollar'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state')
                    ->label('Suburb')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('street', 'city', 'state', 'postcode')
                    ->label('Address')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->paginated([
                5, 10, 25, 50, 100, 'all'
            ])
            ->defaultPaginationPageOption(5)
            // ->extremePaginationLinks()
            ->actions([
                Tables\Actions\ViewAction::make()->label('View')->icon(null),
                Tables\Actions\EditAction::make()->label('Edit')->icon(null),
            ])
            ->bulkActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentTabs::make('customer details')
                    ->columnSpanFull()
                    ->tabs([
                        ComponentTabs\Tab::make('Customer Details')
                            ->schema([
                                ComponentsSection::make('Customer Details')
                                    ->headerActions([
                                        Action::make('edit')->label('Modify')
                                            ->icon('heroicon-s-pencil')
                                            ->url(fn (): string => route('filament.admin.resources.customers.edit', ['record' => $infolist->record])),
                                    ])
                                    ->columns([
                                        'md' => 4,
                                        'sm' => 1,
                                    ])
                                    ->schema([
                                        TextEntry::make('company_name')
                                            ->label('Company Name'),
                                        TextEntry::make('customer_no')
                                            ->label('Customer No'),
                                        TextEntry::make('status')
                                            ->label('Status'),
                                        TextEntry::make('phone')
                                            ->label('Phone Number'),
                                        TextEntry::make('email')
                                            ->label('Email Address'),
                                        TextEntry::make('fax')
                                            ->label('Fax Number'),
                                        TextEntry::make('website')
                                            ->label('Website Address'),
                                    ]),
                                ComponentsSection::make('Delivery Details')
                                    ->columns([
                                        'md' => 4,
                                        'sm' => 1,
                                    ])
                                    ->schema([
                                        TextEntry::make('apply_delivery_charge')
                                            ->label('Apply Delivery Charge'),
                                        TextEntry::make('delivery_charge')
                                            ->label('Delivery Charge')
                                            ->prefix('$'),
                                        TextEntry::make('charge_trigger')
                                            ->label('Charge Trigger')
                                            ->prefix('$'),
                                    ]),
                            ]),

                        ComponentTabs\Tab::make('Contact List')
                            ->schema([

                            ]),
                        ComponentTabs\Tab::make('Discount List')
                            ->schema([
                                // Add discount list fields here
                            ]),
                        ComponentTabs\Tab::make('Consignment List')
                            ->schema([
                                // Add consignment list fields here
                            ]),
                        ComponentTabs\Tab::make('Notes')
                            ->schema([
                                // Add notes fields here
                            ]),
                    ]),
            ]);
    }
}

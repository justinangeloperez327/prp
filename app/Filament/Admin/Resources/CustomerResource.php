<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Status;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Filament\Admin\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'Customer List';

    public static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

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
                            ->placeholder('Company Name')
                            ->columnSpan(2)
                            ->required(),
                        Radio::make('status')
                            ->label('Status')
                            ->columnSpan(1)
                            ->default('active')
                            ->options([
                                'active' => 'Yes',
                                'inactive' => 'No',
                            ]),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->prefixIcon('heroicon-s-phone')
                            ->mask('+61 9999 9999')
                            ->placeholder('+61 9999 9999')
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
                            ->mask('+61 9999 9999')
                            ->placeholder('+61 9999 9999')
                            ->columnSpan(2)
                            ->required(),
                        TextInput::make('website')
                            ->label('Website Address')
                            ->placeholder('https://example.com')
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
                            ->placeholder('Street Address')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('city')
                            ->label('City')
                            ->placeholder('City')
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
                            ->placeholder('Postcode')
                            ->label('Postcode')
                            ->required(),
                    ]),
                Section::make('Delivery Details')
                    ->columns([
                        'md' => 4,
                        'sm' => 1,
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
                            ->numeric()
                            ->placeholder('0.00')
                            ->prefixIcon('heroicon-s-currency-dollar'),
                        TextInput::make('charge_trigger')
                            ->label('Charge Trigger')
                            ->numeric()
                            ->placeholder('0.00')
                            ->prefixIcon('heroicon-s-currency-dollar'),
                    ]),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->label(''),
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
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Phone copied')
                    ->copyMessageDuration(1500)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (Customer $customer): string => match ($customer->status) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class),
            ])
            ->paginated([
                5, 10, 25, 50, 100, 'all',
            ])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks()
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
            RelationManagers\ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerContactResource\Pages;
use App\Models\Contact;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomerContactResource extends Resource
{
    protected static ?string $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'Contact List';

    public static ?int $navigationSort = 2;

    protected static ?string $model = Contact::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact Details')
                    ->columns([
                        'md' => 4,
                        'sm' => 1,
                    ])
                    ->schema([
                        Placeholder::make('')
                            ->columnSpan(2)
                            ->default(function ($record) {
                                return $record?->customer->state;
                            })
                            ->disabled(),
                        TextInput::make('contact_no')
                            ->label('Contact No')
                            ->disabled()
                            ->placeholder('To be assigned'),
                        Radio::make('status')
                            ->label('Status')
                            ->default('inactive')
                            ->options([
                                'active' => 'Yes',
                                'inactive' => 'No',
                            ]),
                        Select::make('title')
                            ->label('Title')
                            ->options([
                                'Mr' => 'Mr',
                                'Mrs' => 'Mrs',
                                'Ms' => 'Ms',
                                'Miss' => 'Miss',
                                'Dr' => 'Dr',
                            ])
                            ->placeholder('Please Select Title')
                            ->required(),
                        TextInput::make('first_name')
                            ->placeholder('Enter first name')
                            ->required(),
                        TextInput::make('last_name')
                            ->placeholder('Enter last name')
                            ->required(),
                        Placeholder::make('')
                            ->disabled(),
                        TextInput::make('direct_phone')
                            ->label('Direct Phone Number')
                            ->prefixIcon('heroicon-s-phone')
                            ->required(),
                        TextInput::make('mobile_phone')
                            ->label('Mobile Number')
                            ->prefixIcon('heroicon-s-phone')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->prefixIcon('heroicon-s-envelope')
                            ->columnSpan(2)
                            ->required(),
                    ]),
                Section::make('Login Details')
                    ->columns([
                        'md' => 4,
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('username')
                            ->placeholder('Enter a unique username')
                            ->required(),
                        TextInput::make('password')
                            ->type('password')
                            ->placeholder('Enter a password to add or modify only')
                            ->required(),
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
                TextColumn::make('first_name')
                    ->label('Contact Name')
                    ->formatStateUsing(fn (Contact $contact) => $contact->first_name.' '.$contact->last_name)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.company_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('direct_phone')
                    ->label('Phone')
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Phone copied')
                    ->copyMessageDuration(1500)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->extraAttributes(['class' => 'italic text-sm'])
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Phone copied')
                    ->copyMessageDuration(1500)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (Contact $contact): string => match ($contact->status) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->searchable()
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
                5, 10, 25, 50, 100, 'all',
            ])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks()
            ->actions([
                Tables\Actions\ViewAction::make()->label('View')->icon(null),
                Tables\Actions\ViewAction::make()->label('Edit')->icon(null),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerContacts::route('/'),
            'create' => Pages\CreateCustomerContact::route('/record/create'),
            'edit' => Pages\EditCustomerContact::route('/{record}/edit'),
        ];
    }
}

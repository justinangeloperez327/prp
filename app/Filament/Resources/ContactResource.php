<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactResource extends Resource
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}

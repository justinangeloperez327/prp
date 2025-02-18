<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OnHoldOrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OnHoldOrderResource extends Resource
{
    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'On Hold';

    public static ?int $navigationSort = 2;

    protected static ?string $model = Order::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOnHoldOrders::route('/'),
            'create' => Pages\CreateOnHoldOrder::route('/create'),
            'edit' => Pages\EditOnHoldOrder::route('/{record}/edit'),
        ];
    }
}

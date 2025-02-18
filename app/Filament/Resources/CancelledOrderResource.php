<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CancelledOrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CancelledOrderResource extends Resource
{
    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Cancelled';

    protected static ?string $model = Order::class;

    public static ?int $navigationSort = 4;

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
            'index' => Pages\ListCancelledOrders::route('/'),
            'create' => Pages\CreateCancelledOrder::route('/create'),
            'edit' => Pages\EditCancelledOrder::route('/{record}/edit'),
        ];
    }
}

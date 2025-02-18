<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcessedOrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProcessedOrderResource extends Resource
{
    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Processed';

    public static ?int $navigationSort = 5;

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
            'index' => Pages\ListProcessedOrders::route('/'),
            'create' => Pages\CreateProcessedOrder::route('/create'),
            'edit' => Pages\EditProcessedOrder::route('/{record}/edit'),
        ];
    }
}

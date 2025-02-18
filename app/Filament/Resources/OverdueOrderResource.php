<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OverdueOrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OverdueOrderResource extends Resource
{
    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Overdue';

    public static ?int $navigationSort = 3;

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
            'index' => Pages\ListOverdueOrders::route('/'),
            'create' => Pages\CreateOverdueOrder::route('/create'),
            'edit' => Pages\EditOverdueOrder::route('/{record}/edit'),
        ];
    }
}

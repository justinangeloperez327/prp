<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductItemResource\Pages;
use App\Models\ProductItem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductItemResource extends Resource
{
    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'Items';

    public static ?int $navigationSort = 3;

    protected static ?string $model = ProductItem::class;

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
            'index' => Pages\ListProductItems::route('/'),
            'create' => Pages\CreateProductItem::route('/create'),
            'edit' => Pages\EditProductItem::route('/{record}/edit'),
        ];
    }
}

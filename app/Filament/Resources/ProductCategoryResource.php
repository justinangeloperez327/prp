<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Models\ProductCategory;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductCategoryResource extends Resource
{
    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'Categories';

    public static ?int $navigationSort = 1;

    protected static ?string $model = ProductCategory::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Details')
                ->columns([
                    'md' => 2,
                    'sm' => 1,
                ])
                ->schema([
                    TextInput::make('name')
                        ->label('Category Name')
                        ->columnSpan(1)
                        ->required(),
                    Radio::make('status')
                        ->label('Status')
                        ->columnSpan(1)
                        ->default('active')
                        ->options([
                            'active' => 'Yes',
                            'inactive' => 'No',
                        ]),
                    TextInput::make('order')
                        ->label('Sort Order')
                        ->columnSpan(1)
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name'),
                TextColumn::make('order')
                    ->label('Order'),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'view' => Pages\ViewProductCategory::route('/{record}'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}

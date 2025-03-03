<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ProductResource\Pages;
use App\Filament\App\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'Types';

    public static ?int $navigationSort = 2;

    protected static ?string $model = Product::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product Details')
                    ->columns([
                        'md' => 2,
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Product Name')
                            ->placeholder('Enter a Product Name')
                            ->required(),
                        Radio::make('status')
                            ->label('Active')
                            ->default('active')
                            ->options([
                                'active' => 'Yes',
                                'inactive' => 'No',
                            ]),
                        Select::make('product_category_id')
                            ->label('Product Category')
                            ->options(ProductCategory::all()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Please select a product category')
                            ->required(),
                        Select::make('table_list_style')
                            ->label('Product Table List Style')
                            ->default('standard')
                            ->options([
                                'standard' => "Standard",
                                'custom' => 'Custom'
                            ])->required(),
                        Textarea::make('type_list')
                            ->label('Product Type')
                            ->default('Default')
                            ->required(),
                        Textarea::make('colour_list')
                            ->label('Product Colour Option List')
                            ->default('Default')
                            ->required(),
                        Textarea::make('description')
                            ->label('Product Description')
                            ->columnSpan(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product Name'),
                TextColumn::make('category.name')
                    ->label('Category'),
                TextColumn::make('promotion')
                    ->label('Promotion'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (Product $product): string => match ($product->status) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

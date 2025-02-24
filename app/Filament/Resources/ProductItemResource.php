<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductItemResource\Pages;
use App\Models\Product;
use App\Models\ProductItem;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductItemResource extends Resource
{
    protected static ?string $navigationGroup = 'Products';

    protected static ?string $navigationLabel = 'Items';

    public static ?int $navigationSort = 3;

    protected static ?string $model = ProductItem::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product Item Details')
                    ->columns([
                        'md' => 6,
                        'sm' => 1,
                    ])
                    ->schema([
                        TextInput::make('size')
                            ->label('Product Item Size')
                            ->columnSpan(3)
                            ->placeholder('Enter a Product Item Size')
                            ->required(),
                        Radio::make('status')
                            ->label('Active')
                            ->columnSpan(3)
                            ->default('active')
                            ->options([
                                'active' => 'Yes',
                                'inactive' => 'No',
                            ]),
                        Select::make('product_id')
                            ->label('Product Type')
                            ->columnSpan(3)
                            ->options(Product::all()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('gsm')
                            ->label('GSM')
                            ->placeholder('Enter GSM value'),
                        TextInput::make('sheets_per_mill_pack')
                            ->label('Sheets per pack')
                            ->placeholder('Enter # sheets'),
                        TextInput::make('sheets_per_pallet')
                            ->label('Sheets per pallet')
                            ->placeholder('Enter # sheets'),
                        Select::make('type')
                            ->columnSpan(3)
                            ->label('Product Type')
                            ->default('default')
                            ->options([
                                'default' => 'Default',
                            ])
                            ->required(),
                    ]),
                Section::make('Product Item Pricing')
                    ->columns([
                        'md' => 4,
                        'sm' => 1
                    ])
                    ->schema([
                        Select::make('unit')
                            ->label('Item Unit')
                            ->options([
                                'sheets' => 'Sheets',
                                'box' => 'Box'
                            ])
                            ->default('sheets')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Item Qty')
                            ->placeholder('Enter Item Quantity'),
                        TextInput::make('price_per_quantity')
                            ->label('Price per Qty')
                            ->placeholder('Enter price per quantity'),
                        TextInput::make('price_broken_mill_pack')
                            ->label('Broken Pack Price')
                            ->placeholder('Enter broken pack price'),
                            ]),
                Section::make('Promotion Pricing')
                    ->columns([
                        'md' => 4,
                        'sm' => 1
                    ])
                    ->schema([
                        TextInput::make('promotion_charge')
                            ->label('Promotion Charge')
                            ->placeholder('Enter Promotion Price'),
                        Select::make('customer_discount')
                            ->label('Customer Discount')
                            ->options([
                                'does_not_apply' => 'Does not apply',
                                'applies' => 'Applies'
                            ])
                            ->default('does_not_apply')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('size'),
                TextColumn::make('product.name')
                    ->label('Product'),
                // TextColumn::make('size'),
                TextColumn::make('gsm'),
                TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (ProductItem $productItem): string => match ($productItem->status) {
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

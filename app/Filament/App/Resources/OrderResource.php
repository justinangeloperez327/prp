<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductItem;
use App\Models\ProductCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Details')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('order_date')
                            ->label('Order Date')
                            ->format('dd-MM-yyyy')
                            ->required(),
                        TextInput::make('order_no')
                            ->label('Order No')
                            ->type('string'),
                        TimePicker::make('order_time')
                            ->label('Order Time'),
                        DatePicker::make('would_like_it_by')
                            ->label('Would Like It By')
                            ->format('dd-MM-yyyy'),
                        TextInput::make('status')
                            ->label('Status')
                            ->type('string')
                            ->default('draft')
                            ->disabledOn('create'),
                    ]),
                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('product_category_id')
                                ->options(
                                    ProductCategory::orderBy('name')->pluck('name', 'id')->toArray()

                                )
                                ->label('Category')
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (callable $set) {
                                    $set('product_id', null);
                                    $set('product_item_id', null);
                                    $set('colour', null);
                                    $set('instructions', null);
                                    $set('quantity', null);
                                }),
                                Select::make('product_id')
                                    ->options(function (callable $get) {
                                        $productCategoryId = $get('product_category_id');
                                        if ($productCategoryId) {
                                            return Product::where('product_category_id', $productCategoryId)->pluck('name', 'id')->toArray();
                                        }
                                        return [];
                                    })
                                    ->label('Product')
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('product_item_id', null);
                                        $set('colour', null);
                                        $set('instructions', null);
                                        $set('quantity', null);
                                    }),
                                Select::make('product_item_id')
                                    ->options(function (callable $get) {
                                        $productId = $get('product_id');
                                        if ($productId) {
                                            return ProductItem::where('product_id', $productId)->pluck('size', 'id')->toArray();
                                        }
                                        return [];
                                    })
                                    ->label('Size')
                                    ->searchable()
                                    ->required()
                                    ->disabled(fn (callable $get) => !$get('product_id'))
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('instructions', null);
                                        $set('quantity', null);
                                    }),
                                Select::make('colour')
                                    ->options(function (callable $get) {
                                        $productId = $get('product_id');
                                        if ($productId) {
                                            $product = Product::find($productId);
                                            if ($product && $product->colour_list) {
                                                $colours = explode(';', $product->colour_list);
                                                return array_combine($colours, $colours);
                                            }
                                        }
                                        return [];
                                    })
                                    ->label('Colour')
                                    ->searchable()
                                    ->required()
                                    ->disabled(fn (callable $get) => !$get('product_id') || !Product::find($get('product_id'))?->colour_list)
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('instructions', null);
                                        $set('quantity', null);
                                    }),
                                TextInput::make('Instructions')
                                    ->label('Instructions')
                                    ->required()
                                    ->disabled(fn (callable $get) => !$get('product_item_id')),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->required()
                                    ->disabled(fn (callable $get) => !$get('product_item_id')),
                                TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->disabled(),
                            ])
                            ->columns(7)
                            ->createItemButtonLabel('Add Item'),
                    ]),
                Section::make('Order Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('additional_instructions')
                            ->label('Additional Instructions')
                            ->required(),
                        TextInput::make('purchase_order_no')
                            ->label('Purchase Order No')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_no')
                    ->label('Order No'),
                TextColumn::make('order_time')
                    ->label('Date In'),
                TextColumn::make('would_like_it_by')
                    ->label('Required By'),
                TextColumn::make('user.name')
                    ->label('Customer'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (Order $order): string => match ($order->status) {
                        'new' => 'green',
                        'on-hold' => 'yellow',
                        'overdue' => 'red',
                        'cancelled' => 'gray',
                        'processed' => 'blue',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

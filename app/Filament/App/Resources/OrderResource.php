<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Customer;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use App\Models\ProductItem;
use App\Models\ProductCategory;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\App\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Orders';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', OrderStatus::New)->where('user_id', Auth::id())->count();
    }

    public static function form(Form $form): Form
    {
        $deliveryCharge = 0;
        $user = Auth::user();
        $contact = Contact::where('user_id', $user->id)->first();
        $customer = Customer::where('id', $contact->customer_id)->first();
        $deliveryCharge = $customer->delivery_charge;

        return $form
            ->schema([
                Section::make('Order Details')
                    ->columns(4)
                    ->schema([
                        DatePicker::make('order_date')
                            ->label('Order Date')
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->default(now()->format('Y-m-d'))
                            ->disabled(true),
                        TimePicker::make('order_time')
                            ->label('Order Time')
                            ->format('H:i')
                            ->default(now()->format('H:i'))
                            ->seconds(false)
                            ->disabled(true),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'new' => 'New',
                                'on-hold' => 'On Hold',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                                'processed' => 'Processed',
                            ])
                            ->default('draft')
                            ->disabledOn('create'),
                        DatePicker::make('would_like_it_by')
                            ->label('Would Like It By')
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),

                            Repeater::make('items')
                                ->columnSpanFull()
                                ->relationship('items')
                                ->columns(6)
                                ->addAction(fn (Action $action) => $action->icon('heroicon-m-plus'))
                                ->addActionLabel(false)
                                ->addActionAlignment('right')
                                ->reorderable()
                                ->schema([
                                    Select::make('product_category_id')
                                        ->options(
                                            ProductCategory::orderBy('name')->pluck('name', 'id')->toArray()

                                        )
                                        ->label('Category')
                                        ->placeholder('Select a category')
                                        ->searchable()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('product_id', null);
                                            $set('product_item_id', null);
                                            $set('product_colour', null);
                                            $set('special_instructions', null);
                                            $set('quantity', null);
                                        }),
                                    Select::make('product_id')
                                        ->options(function (Get $get) {
                                            $productCategoryId = $get('product_category_id');
                                            if ($productCategoryId) {
                                                return Product::where('product_category_id', $productCategoryId)->pluck('name', 'id')->toArray();
                                            }
                                            return [];
                                        })
                                        ->label('Product')
                                        ->placeholder('Select a product')
                                        ->searchable()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('product_item_id', null);
                                            $set('product_colour', null);
                                            $set('special_instructions', null);
                                            $set('quantity', null);
                                        }),
                                    Select::make('product_item_id')
                                        ->options(function (Get $get) {
                                            $productId = $get('product_id');
                                            if ($productId) {
                                                return ProductItem::where('product_id', $productId)->pluck('size', 'id')->toArray();
                                            }
                                            return [];
                                        })
                                        ->label('Size')
                                        ->searchable()
                                        ->required()
                                        ->disabled(fn (Get $get) => !$get('product_id'))
                                        ->reactive()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('special_instructions', null);
                                            $set('quantity', null);
                                        }),
                                    Select::make('product_colour')
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
                                            $set('special_instructions', null);
                                            $set('quantity', null);
                                        }),
                                    // Textarea::make('special_instructions')
                                    //     ->label('Instructions')
                                    //     ->disabled(fn (Get $get) => !$get('product_item_id')),
                                    TextInput::make('quantity')
                                        ->label('Qty')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->minValue(1)
                                        ->step(1)
                                        ->disabled(fn (Get $get) => !$get('product_item_id'))
                                        ->afterStateUpdated(function (Set $set, Get $get) {
                                            $quantity = $get('quantity');
                                            $productItemId = $get('product_item_id');
                                            if ($quantity && $productItemId) {
                                                $pricePerQuantity = ProductItem::find($productItemId)->price_per_quantity ?? 0;
                                                $totalItems = ($quantity * $pricePerQuantity) / 1000;
                                                $set('total', $totalItems);

                                            } else {
                                                $set('total', null);
                                            }
                                        }),
                                    TextInput::make('total')
                                        ->label('Total')
                                        ->numeric()
                                        ->prefix('$')
                                        ->required()
                                        ->readOnly(),
                                ])
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                    $set('grand_total', $get('delivery_charge'));
                                    $items = $get('items');
                                    $grandTotal = 0;
                                    foreach ($items as $item) {
                                        $grandTotal += $item['total'];
                                    }

                                    $grandTotal = $get('grand_total');
                                    $set('grand_total', $grandTotal);
                            }),
                            TextInput::make('delivery_charge')
                                ->label('Delivery Charge')
                                ->columnSpan(2)
                                ->default($deliveryCharge)
                                ->numeric()
                                ->prefix('$')
                                ->readOnly(),
                            TextInput::make('grand_total')
                                ->label('Total (ex. GST)')
                                ->columnSpan(2)
                                ->default($deliveryCharge)
                                ->numeric()
                                ->prefix('$')
                                ->reactive()
                                ->readOnly(),
                            TextInput::make('purchase_order_no')
                                ->columnSpan(2)
                                ->label('Purchase Order No')
                                ->required(),
                            Textarea::make('additional_instructions')
                                ->columnSpan(2)
                                ->label('Additional Instructions'),
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

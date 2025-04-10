<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductItem;
use App\Models\ProductCategory;
use Filament\Resources\Resource;
use App\Enums\DeliveryChargeTypes;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Admin\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Current Orders';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()
            ->when(Auth::user()->hasRole('customer'), function (Builder $query) {
                $contact = Contact::where('user_id', Auth::id())->first();
                $customer = Customer::where('id', $contact->customer_id)->first();

                return $query->where('customer_id', $customer->id);
            })
            ->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'sm' => 3,
                    'xl' => 6,
                ])->schema([
                    Section::make('Customer Details')
                        ->columns(4)
                        ->schema([
                            Select::make('customer_id')
                                ->label('Customer')
                                ->required()
                                ->relationship('customer', 'company', function (Builder $query) {
                                    return $query->where('status', 'active');
                                })
                                ->preload()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $customer = Customer::find($get('customer_id'));
                                    if ($customer) {
                                        $set('grand_total', 0);
                                        $set('delivery_charge', $customer->delivery_charge);
                                        $set('charge_trigger', $customer->charge_trigger);
                                        $set('apply_delivery_charge', $customer->apply_delivery_charge);
                                    }
                                })
                                ->live(),
                        ])->hidden(fn () => Auth::user()->hasRole('customer')),
                    Section::make('Order Details')
                        ->columns(4)
                        ->schema([
                            DatePicker::make('order_date')
                                ->label('Order Date')
                                ->format('Y-m-d')
                                ->displayFormat('d/m/Y')
                                ->native(false)
                                ->default(now()->format('Y-m-d'))
                                ->readOnly(),
                            TimePicker::make('order_time')
                                ->label('Order Time')
                                ->format('H:i')
                                ->default(now()->format('H:i'))
                                ->seconds(false)
                                ->readOnly(),
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
                                ->minDate(today()->addDay())
                                ->native(false)
                                ->required(),

                        ]),

                    Section::make('')
                        ->schema([
                            Repeater::make('items')
                                ->label('Items')
                                ->columnSpanFull()
                                ->relationship('items')
                                ->columns(8)
                                ->schema([
                                    Select::make('product_category_id')
                                        ->options(
                                            ProductCategory::orderBy('name')->pluck('name', 'id')->toArray()
                                        )
                                        ->label('Category')
                                        ->placeholder('Select a category')
                                        ->searchable()
                                        ->required()
                                        ->live()
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
                                        ->live()
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
                                        ->disabled(fn (Get $get) => ! $get('product_id'))
                                        ->live()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('special_instructions', null);
                                            $set('quantity', null);
                                        }),
                                    Select::make('product_colour')
                                        ->options(function (Get $get) {
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
                                        ->disabled(fn (Get $get) => ! $get('product_id') || ! Product::find($get('product_id'))?->colour_list)
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, $state) {
                                            if (! $state) {
                                                $set('product_colour', null); // Clear the field if no valid color is selected
                                            }
                                            $set('special_instructions', null);
                                            $set('quantity', null);
                                        }),
                                    TextInput::make('quantity')
                                        ->label('Quantity')
                                        ->numeric()
                                        ->required()
                                        ->live(debounce: 1000)
                                        ->minValue(1)
                                        ->step(1)
                                        ->disabled(fn (Get $get) => ! $get('product_item_id'))
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            self::updateTotalPerItem($get, $set);
                                        }),
                                    TextInput::make('total')
                                        ->label('Total')
                                        ->numeric()
                                        ->prefix('$')
                                        ->live(debounce: 500)
                                        ->readOnly(),
                                    TextInput::make('special_instructions')
                                        ->label('Special Instructions')
                                        ->placeholder('Any special instructions for this item')
                                        ->maxLength(255)
                                        ->columnSpan(2),
                                ])
                                ->addAction(fn (Action $action) => $action->icon('heroicon-m-plus')->color('primary'))
                                ->addActionLabel('Add Item')
                                ->addActionAlignment('right')
                                ->reorderable()
                                ->live()
                                ->minItems(1)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    self::updateTotals($get, $set);
                                }),
                        ]),
                    Section::make('')
                        ->columns(4)
                        ->schema([
                            TextInput::make('delivery_charge')
                                ->label('Delivery Charge')
                                ->columnSpan(2)
                                ->numeric()
                                ->live()
                                ->prefix('$')
                                ->readOnly(),
                            TextInput::make('grand_total')
                                ->label('Total (ex. GST)')
                                ->columnSpan(2)
                                ->inputMode('decimal')
                                ->step('0.01')
                                ->numeric()
                                ->prefix('$')
                                ->live(debounce: 1000)
                                ->readOnly()
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    self::updateTotals($get, $set);
                                }),
                            TextInput::make('purchase_order_no')
                                ->columnSpan(2)
                                ->label('Purchase Order No')
                                ->required(),
                            Textarea::make('additional_instructions')
                                ->columnSpan(2)
                                ->label('Additional Instructions'),
                        ]),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->hasRole('customer')) {
                    $contact = Contact::where('user_id', Auth::id())->first();
                    $customer = Customer::where('id', $contact->customer_id)->first();

                    return $query->where('customer_id', $customer->id);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('order_no')
                    ->label('Order No')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('order_date')
                    ->label('Order Date')
                    ->sortable()
                    ->searchable()
                    ->date('d/m/Y'),
                TextColumn::make('order_time')
                    ->label('Order Time')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('would_like_it_by')
                    ->label('Required By')
                    ->searchable()
                    ->sortable()
                    ->date('d/m/Y'),
                TextColumn::make('customer.company')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (Order $order): string => match ($order->status) {
                        'new' => 'green',
                        'on-hold' => 'yellow',
                        'overdue' => 'red',
                        'cancelled' => 'gray',
                        'processed' => 'blue',
                        default => 'gray',
                    })
                    ->searchable(),
            ])
            ->defaultSort('order_no', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->multiple()
                    ->options([
                        'draft' => 'Draft',
                        'new' => 'New',
                        'on-hold' => 'On Hold',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                        'processed' => 'Processed',
                    ]),
            ])
            ->paginated([5, 10, 25, 50, 100])
            ->defaultPaginationPageOption(5)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
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

    public static function updateTotalPerItem(Get $get, Set $set): void
    {
        $quantity = $get('quantity');
        $productItemId = $get('product_item_id');
        if ($quantity && $productItemId) {
            $pricePerQuantity = ProductItem::find($productItemId)->price_per_quantity ?? 0;
            $totalItems = ($quantity * $pricePerQuantity) / 1000;
            $totalItems = number_format($totalItems, 2);
            $set('total', $totalItems);
        } else {
            $set('total', null);
        }
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        if (Auth::user()->hasRole('customer')) {
            $contact = Contact::where('user_id', Auth::id())->firstOrFail();
            $customer = Customer::where('id', $contact->customer_id)->firstOrFail();

            $set('delivery_charge', $customer->delivery_charge);
            $set('charge_trigger', $customer->charge_trigger);
            $set('apply_delivery_charge', $customer->apply_delivery_charge);
        }

        $selectedItems = collect($get('items'))->filter(fn ($item) => ! empty($item['product_item_id']) && ! empty($item['quantity']));
        $prices = ProductItem::whereIn('id', $selectedItems->pluck('product_item_id'))->pluck('price_per_quantity', 'id');

        $subTotal = $selectedItems->sum(function ($item) use ($prices) {
            return ($item['quantity'] * $prices[$item['product_item_id']]) / 1000;
        });

        $grandTotal = 0;

        $deliveryCharge = $get('delivery_charge');
        $chargeTrigger = $get('charge_trigger');
        $applyDeliveryCharge = $get('apply_delivery_charge');

        if ($applyDeliveryCharge === DeliveryChargeTypes::NONE->value) {
            $deliveryCharge = 0;
        }

        if ($applyDeliveryCharge === DeliveryChargeTypes::FIXED->value) {
            $grandTotal = $subTotal + $deliveryCharge;
        }

        if ($applyDeliveryCharge === DeliveryChargeTypes::MINIMUM->value) {
            if ($subTotal < $chargeTrigger) {
                $grandTotal = $subTotal + $deliveryCharge;
            }

            if ($subTotal > $chargeTrigger) {
                $deliveryCharge = 0;
            }
        }

        $grandTotal = round($grandTotal, 2);
        $set('grand_total', $grandTotal);
        $set('delivery_charge', $deliveryCharge);
    }
}

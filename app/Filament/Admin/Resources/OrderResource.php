<?php

namespace App\Filament\Admin\Resources;

use App\Enums\DeliveryChargeTypes;
use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductItem;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                                        $set('applied_delivery_charge', $customer->delivery_charge);
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
                                ->minDate(today())
                                ->native(false)
                                ->required()
                                ->closeOnDateSelection()
                                ->disabledDates(function (): array {
                                    $dates = [];
                                    $startDate = now();
                                    $endDate = now()->addYear();

                                    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                                        if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                                            $dates[] = $date->format('Y-m-d');
                                        }
                                    }

                                    return $dates;
                                }),
                        ]),

                    Section::make('')
                        ->schema([
                            Repeater::make('items')
                                ->label('Items')
                                ->relationship('items')
                                ->schema([
                                    Split::make([
                                        Fieldset::make('')
                                            ->columns(1)
                                            ->schema([
                                                Select::make('product_category_id')
                                                    ->inlineLabel()
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
                                                    ->inlineLabel()
                                                    ->options(function (Get $get) {
                                                        $productCategoryId = $get('product_category_id');
                                                        if ($productCategoryId) {
                                                            return Product::where('product_category_id', $productCategoryId)->pluck('name', 'id')->toArray();
                                                        }

                                                        return [];
                                                    })
                                                    ->disabled(fn (Get $get) => ! $get('product_category_id'))
                                                    ->label('Product')
                                                    ->placeholder('Select a product')
                                                    ->searchable()
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set) {
                                                        $set('product_item_id', null);
                                                        $set('product_colour', null);
                                                        $set('special_instructions', null);
                                                        $set('quantity', 0);
                                                    }),
                                                Select::make('product_item_id')
                                                    ->inlineLabel()
                                                    ->options(function (Get $get) {
                                                        $productId = $get('product_id');
                                                        if ($productId) {
                                                            $productItems = ProductItem::where('product_id', $productId)->orderBy('gsm')->orderBy('size')->get();

                                                            return $productItems->mapWithKeys(function ($item) {
                                                                $label = $item->size.($item->gsm ? ' ('.$item->gsm.' gsm)' : '');

                                                                return [$item->id => $label];
                                                            })->toArray();
                                                        }

                                                        return [];
                                                    })
                                                    ->label('Size')
                                                    ->placeholder('Select a size')
                                                    ->searchable()
                                                    ->required()
                                                    ->disabled(fn (Get $get) => ! $get('product_id'))
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $set('special_instructions', null);
                                                        $quantity = 1;
                                                        $productItemId = $get('product_item_id');
                                                        if ($productItemId) {
                                                            $productItem = ProductItem::find($productItemId);
                                                            if ($productItem && $productItem->sheets_per_mill_pack > 0) {
                                                                $quantity = $productItem->sheets_per_mill_pack;
                                                            }
                                                        }
                                                        $set('quantity', $quantity);
                                                        self::updateTotalPerItem($get, $set);
                                                    }),
                                                Select::make('product_colour')
                                                    ->inlineLabel()
                                                    ->options(function (Get $get) {
                                                        $productId = $get('product_id');
                                                        if ($productId) {
                                                            $product = Product::find($productId);
                                                            if ($product && $product->colour_list) {
                                                                $colours = explode(';', $product->colour_list);
                                                                sort($colours);

                                                                return array_combine($colours, $colours);
                                                            }
                                                        }

                                                        return [];
                                                    })
                                                    ->label('Colour')
                                                    ->placeholder('Select a colour')
                                                    ->searchable()
                                                    ->required()
                                                    ->disabled(fn (Get $get) => ! $get('product_id') || ! Product::find($get('product_id'))?->colour_list),
                                                Textarea::make('special_instructions')
                                                    ->label('Instructions')
                                                    ->inlineLabel()
                                                    ->placeholder('Any special instructions for this item')
                                                    ->maxLength(255),
                                            ])
                                            ->extraAttributes(['class' => 'border-none']),
                                        Fieldset::make('')
                                            ->columns(1)
                                            ->schema([
                                                TextInput::make('quantity')
                                                    ->label('Quantity (Sheets)')
                                                    ->inlineLabel()
                                                    ->numeric()
                                                    ->required()
                                                    ->live()
                                                    ->step(1)
                                                    ->disabled(fn (Get $get) => ! $get('product_item_id'))
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        self::updateTotalPerItem($get, $set);
                                                    })
                                                    ->minValue(function (Get $get) {
                                                        $productItemId = $get('product_item_id');
                                                        if ($productItemId) {
                                                            $productItem = ProductItem::find($productItemId);
                                                            if ($productItem && $productItem->sheets_per_mill_pack > 0) {
                                                                return $productItem->sheets_per_mill_pack;
                                                            }
                                                        }

                                                        return 1;
                                                    })
                                                    ->step(function (Get $get) {
                                                        $productItemId = $get('product_item_id');
                                                        if ($productItemId) {
                                                            $productItem = ProductItem::find($productItemId);
                                                            if ($productItem && $productItem->sheets_per_mill_pack > 0) {
                                                                return $productItem->sheets_per_mill_pack;
                                                            }
                                                        }

                                                        return 1;
                                                    })
                                                    ->helperText(function (Get $get) {
                                                        $productItemId = $get('product_item_id');
                                                        if ($productItemId) {
                                                            $productItem = ProductItem::find($productItemId);
                                                            if ($productItem && $productItem->sheets_per_mill_pack > 0) {
                                                                return 'Minimum quantity: '.number_format($productItem->sheets_per_mill_pack).' sheets';
                                                            }
                                                        }

                                                        return '';
                                                    }),

                                                Placeholder::make('')
                                                    ->label('Box per Pack')
                                                    ->inlineLabel()
                                                    ->visible(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));

                                                        return $productItem?->unit === 'Box';
                                                    })
                                                    ->content(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->quantity !== null) {
                                                            return $productItem->quantity;
                                                        }

                                                        return 1;
                                                    }),
                                                Placeholder::make('')
                                                    ->label('Price per each')
                                                    ->inlineLabel()
                                                    ->visible(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));

                                                        return $productItem?->unit === 'Box';
                                                    })
                                                    ->content(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->price_per_quantity !== null) {
                                                            return '$'.number_format($productItem->price_per_quantity);
                                                        }

                                                        return '$0.00';
                                                    }),
                                                // Sheets
                                                Placeholder::make('')
                                                    ->label('Sheets per Pack')
                                                    ->inlineLabel()
                                                    ->visible(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));

                                                        return $productItem?->unit === 'Sheets';
                                                    })
                                                    ->content(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->sheets_per_mill_pack !== null) {
                                                            return $productItem->sheets_per_mill_pack;
                                                        }

                                                        return '1';
                                                    }),
                                                Placeholder::make('')
                                                    ->label(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->quantity > 0) {
                                                            return 'Sheets per '.$productItem->quantity.' sheets (qty less than 2 packs)';
                                                        }

                                                        return '';
                                                    })
                                                    ->inlineLabel()
                                                    ->visible(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));

                                                        return $productItem?->unit === 'Sheets';
                                                    })
                                                    ->content(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->sheets_per_mill_pack !== null) {
                                                            return '$'.number_format($productItem->price_broken_mill_pack);
                                                        }

                                                        return '$0.00';
                                                    }),
                                                Placeholder::make('')
                                                    ->label(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->quantity > 0) {
                                                            return 'Sheets per '.$productItem->quantity.' sheets (qty 2 packs or more)';
                                                        }

                                                        return '';
                                                    })
                                                    ->inlineLabel()
                                                    ->visible(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));

                                                        return $productItem?->unit === 'Sheets';
                                                    })
                                                    ->content(function (Get $get) {
                                                        $productItem = ProductItem::find($get('product_item_id'));
                                                        if ($productItem?->sheets_per_mill_pack !== null) {
                                                            return '$'.number_format($productItem->price_per_quantity);
                                                        }

                                                        return '$0.00';
                                                    }),
                                            ])->extraAttributes(['class' => 'border-none']),
                                    ]),
                                ])
                                ->addAction(fn (Action $action) => $action->icon('heroicon-m-plus')->color('primary'))
                                ->addActionLabel('Add Item')
                                ->addActionAlignment('right')
                                ->live()
                                ->minItems(1)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    self::updateTotals($get, $set);
                                }),
                        ]),
                    Section::make('')
                        ->columns(4)
                        ->schema([
                            TextInput::make('applied_delivery_charge')
                                ->label('Delivery Charge')
                                ->columnSpan(2)
                                ->numeric()
                                ->live()
                                ->readOnly(fn () => Auth::user()->hasRole('customer'))
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    self::updateTotals($get, $set);
                                })
                                ->prefix('$'),
                            TextInput::make('grand_total')
                                ->label('Total (ex. GST)')
                                ->columnSpan(2)
                                ->inputMode('decimal')
                                ->step('0.01')
                                ->numeric()
                                ->hidden()
                                ->prefix('$')
                                ->live(debounce: 1000)
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    self::updateTotals($get, $set);
                                }),
                            Placeholder::make('grand_total')
                                ->label('Total (ex. GST)')
                                ->columnSpan(2)
                                ->live(debounce: 2000)
                                ->content(function (Get $get) {
                                    $grandTotal = $get('grand_total');

                                    if ($grandTotal !== null) {
                                        return '$'.$grandTotal;
                                    }

                                    return '$0.00';
                                }),
                            Textarea::make('additional_instructions')
                                ->columnSpan(2)
                                ->label('Additional Instructions'),
                            TextArea::make('internal_notes')
                                ->label('Internal Notes')
                                ->hidden(fn () => Auth::user()->hasRole('customer'))
                                ->columnSpan(2)
                                ->placeholder('Internal notes for this order'),
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
            $set('total', 0);
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
            $set('applied_delivery_charge', $customer->delivery_charge);
        }

        $selectedItems = collect($get('items'))->filter(fn ($item) => ! empty($item['product_item_id']) && ! empty($item['quantity']));
        $prices = ProductItem::whereIn('id', $selectedItems->pluck('product_item_id'))->pluck('price_per_quantity', 'id');
        $discounts = Discount::whereIn('product_id', $selectedItems->pluck('product_id'))
            ->where('customer_id', $get('customer_id'))
            ->where('status', 'active')
            ->pluck('discount', 'product_id');

        $discounts = $discounts->map(function ($discount) {
            return $discount * 100; // Convert to percentage
        });

        $subTotal = $selectedItems->sum(function ($item) use ($prices, $discounts) {
            $price = $prices[$item['product_item_id']] ?? 0;
            $quantity = $item['quantity'];
            $productId = $item['product_id'] ?? null;
            $discount = $discounts[$productId] ?? 0;

            $itemTotal = ($quantity * $price) / 1000;
            $discountedTotal = $itemTotal * (1 - ($discount / 100));

            return $discountedTotal;
        });

        $grandTotal = $subTotal;

        $deliveryCharge = $get('delivery_charge');
        $chargeTrigger = $get('charge_trigger');
        $applyDeliveryCharge = $get('apply_delivery_charge');

        if ($applyDeliveryCharge === DeliveryChargeTypes::NONE->value) {
            $deliveryCharge = 0;
            $set('applied_delivery_charge', $deliveryCharge);
        }

        if ($applyDeliveryCharge === DeliveryChargeTypes::FIXED->value) {
            $grandTotal = $subTotal + $deliveryCharge;
            $set('applied_delivery_charge', $deliveryCharge);
        }

        if ($applyDeliveryCharge === DeliveryChargeTypes::MINIMUM->value) {
            if ($subTotal < $chargeTrigger) {
                $grandTotal = $subTotal + $deliveryCharge;
                $set('applied_delivery_charge', $deliveryCharge);
            }

            if ($subTotal > $chargeTrigger) {
                $set('applied_delivery_charge', 0);
            }
        }

        $grandTotal = round($grandTotal, 2);
        $set('grand_total', $grandTotal);
    }
}

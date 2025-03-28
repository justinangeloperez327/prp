<?php

namespace App\Filament\Admin\Pages;

use App\Models\Order;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class CancelledOrders extends Page implements HasActions, HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Cancelled Orders';

    public static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.cancelled-orders';

    public static function getNavigationBadge(): ?string
    {
        return Order::query()
            ->where('status', 'cancelled')
            ->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('status', 'cancelled')
            )
            ->columns([
                TextColumn::make('order_no')
                    ->label('Order No')
                    ->sortable(),
                TextColumn::make('order_time')
                    ->label('Date In'),
                TextColumn::make('would_like_it_by')
                    ->label('Required By')
                    ->sortable(),
                TextColumn::make('customer.company')
                    ->label('Customer'),
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
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Order $record) => route('filament.admin.resources.orders.view', $record)),
                Tables\Actions\EditAction::make()
                    ->url(fn (Order $record) => route('filament.admin.resources.orders.edit', $record)),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}

<?php

namespace App\Filament\App\Pages;

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

class OnHoldOrders extends Page implements HasActions, HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'On Hold Orders';

    public static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.on-hold-orders';

    public static function getNavigationBadge(): ?string
    {
        return Order::query()
            ->where('status', 'on-hold')
            ->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('status', 'on-hold')
            )
            ->columns([
                TextColumn::make('order_no')
                    ->label('Order No'),
                TextColumn::make('order_time')
                    ->label('Date In'),
                TextColumn::make('would_like_it_by')
                    ->label('Required By'),
                TextColumn::make('customer.company_name')
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
                Tables\Actions\ViewAction::make()
                    ->url(fn (Order $record) => route('filament.app.resources.orders.view', $record)),
                Tables\Actions\EditAction::make()
                    ->url(fn (Order $record) => route('filament.app.resources.orders.edit', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Pages;

use Filament\Tables;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class ProcessedOrders extends Page implements HasActions, HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Processed Orders';

    public static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.processed-orders';

    public static function getNavigationBadge(): ?string
    {
        return Order::query()
            ->where('status', 'processed')
            ->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->when(Auth::user()->hasRole('customer'), function ($query) {
                        return $query->where('customer_id', Auth::user()->contact->customer_id);
                    })
                    ->where('status', 'processed')
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

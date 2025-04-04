<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->orderBy('order_no', 'desc')
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_no')
                    ->label('Order No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_time')
                    ->label('Date In'),
                Tables\Columns\TextColumn::make('would_like_it_by')
                    ->label('Would Like It By'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('AUD', true)
                    ->sortable(),
            ])->defaultPaginationPageOption(5);
    }
}

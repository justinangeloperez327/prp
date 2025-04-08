<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $newOrdersCount = Order::where('status', 'new')->count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        return [
            Stat::make('New Orders', $newOrdersCount),
            Stat::make('Total Customers', $totalCustomers),
            Stat::make('Total Products', $totalProducts),
        ];
    }
}

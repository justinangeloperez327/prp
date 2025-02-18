<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStats extends BaseWidget
{
    protected ?string $heading = 'Customers';

    protected function getStats(): array
    {
        return [
            Stat::make('Active Customers', Customer::where('status', 'active')->count()),
            Stat::make('Inactive Customers', Customer::where('status', 'inactive')->count()),
        ];
    }
}

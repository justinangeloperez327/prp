<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Total Customers';

    protected function getData(): array
    {
        $customers = Customer::query()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $currentMonth = date('n');
        $months = array_merge(range($currentMonth, 12), range(1, $currentMonth - 1));
        $labels = array_map(fn ($month) => date('M', mktime(0, 0, 0, $month, 1)), $months);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => array_values($customers),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

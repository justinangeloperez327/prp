<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
                [
                    'label' => 'Processed',
                    'data' => [0, 5, 2, 1, 10, 15, 20, 35, 30, 20, 40, 45],
                    'backgroundColor' => '#FFCE56',
                    'borderColor' => '#FFDB9B',
                ],
                [
                    'label' => 'Overdue',
                    'data' => [0, 2, 1, 0, 5, 10, 15, 25, 20, 15, 30, 35],
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF9AA2',
                ],
                [
                    'label' => 'Cancelled',
                    'data' => [0, 3, 2, 1, 7, 12, 20, 30, 25, 20, 35, 40],
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#7ED1D1',
                ],
                [
                    'label' => 'On Hold',
                    'data' => [0, 1, 0, 0, 3, 5, 10, 15, 12, 10, 20, 25],
                    'backgroundColor' => '#9966FF',
                    'borderColor' => '#B3A0FF',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    public function getDescription(): ?string
    {
        return 'The number of orders per month.';
    }

    protected function getType(): string
    {
        return 'line';
    }
}

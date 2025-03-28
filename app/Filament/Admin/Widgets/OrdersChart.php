<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';

    protected function getData(): array
    {
        $processedOrdersData = Order::where('status', 'processed')
            ->selectRaw('MONTH(order_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        $newOrdersData = Order::where('status', 'new')
            ->selectRaw('MONTH(order_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        $overdueOrdersData = Order::where('status', 'overdue')
            ->selectRaw('MONTH(order_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        $cancelledOrdersData = Order::where('status', 'cancelled')
            ->selectRaw('MONTH(order_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        $onHoldOrdersData = Order::where('status', 'on-hold')
            ->selectRaw('MONTH(order_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = range(1, 12);
        $processedOrders = array_replace(array_fill_keys($months, 0), $processedOrdersData);
        $newOrders = array_replace(array_fill_keys($months, 0), $newOrdersData);
        $overdueOrders = array_replace(array_fill_keys($months, 0), $overdueOrdersData);
        $cancelledOrders = array_replace(array_fill_keys($months, 0), $cancelledOrdersData);
        $onHoldOrders = array_replace(array_fill_keys($months, 0), $onHoldOrdersData);

        $currentMonth = date('n');
        $months = array_merge(range($currentMonth, 12), range(1, $currentMonth - 1));
        $labels = array_map(fn($month) => date('M', mktime(0, 0, 0, $month, 1)), $months);
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'New',
                    'data' => array_values($newOrders),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
                [
                    'label' => 'Processed',
                    'data' => array_values($processedOrders),
                    'backgroundColor' => '#FFCE56',
                    'borderColor' => '#FFDB9B',
                ],
                [
                    'label' => 'Overdue',
                    'data' => array_values($overdueOrders),
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF9AA2',
                ],
                [
                    'label' => 'Cancelled',
                    'data' => array_values($cancelledOrders),
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#7ED1D1',
                ],
                [
                    'label' => 'On Hold',
                    'data' => array_values($onHoldOrders),
                    'backgroundColor' => '#9966FF',
                    'borderColor' => '#B3A0FF',
                ],
            ],
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

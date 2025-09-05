<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Orders by Status';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $statuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'returned' => 'Returned',
            'refunded' => 'Refunded',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
        ];

        $data = [];
        $labels = [];
        $colors = [
            '#6B7280', // gray - pending
            '#3B82F6', // blue - confirmed
            '#EAB308', // yellow - processing
            '#8B5CF6', // purple - shipped
            '#10B981', // green - delivered
            '#F97316', // orange - returned
            '#EF4444', // red - refunded
            '#64748B', // secondary - cancelled
            '#059669', // success - completed
        ];

        $colorIndex = 0;
        foreach ($statuses as $status => $label) {
            $count = Order::where('status', $status)->count();
            $data[] = $count;
            $labels[] = $label;
            $colorIndex++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
} 
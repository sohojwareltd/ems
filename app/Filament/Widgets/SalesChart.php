<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Overview';
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        try {
            return Auth::user()?->can('dashboard.sales') ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getData(): array
    {
        $days = collect();
        $sales = collect();
        
        // Get last 30 days of data
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days->push($date->format('M j'));
            
            $dailySales = Order::whereDate('created_at', $date)->sum('total');
            $sales->push($dailySales);
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Daily Sales',
                    'data' => $sales->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
} 
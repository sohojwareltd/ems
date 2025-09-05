<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\OrderLine;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TopProducts extends ChartWidget
{
    protected static ?string $heading = 'Top Selling Products';

    public static function canView(): bool
    {
        try {
            return Auth::user()?->can('dashboard.products') ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getData(): array
    {
        $topProducts = OrderLine::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $labels = $topProducts->pluck('product.name')->toArray();
        $data = $topProducts->pluck('total_sold')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Units Sold',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#06b6d4',
                        '#84cc16',
                        '#f97316',
                        '#ec4899',
                        '#6366f1',
                    ],
                    'borderWidth' => 0,
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
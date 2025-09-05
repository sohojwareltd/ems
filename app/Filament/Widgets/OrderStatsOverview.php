<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $thisDay = now()->startOfDay();
        
        return [
            Stat::make('Total Orders', Order::count())
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Today\'s Orders', Order::whereDate('created_at', $today)->count())
                ->description('Orders created today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success')
                ->chart([3, 5, 2, 8, 4, 6, 9]),

            Stat::make('Confirmed Orders', Order::where('status', 'confirmed')->count())
                ->description('Ready for processing')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info')
                ->chart([2, 4, 6, 8, 10, 12, 14]),

            Stat::make('Processing Orders', Order::where('status', 'processing')->count())
                ->description('Currently being processed')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('warning')
                ->chart([1, 3, 5, 7, 9, 11, 13]),

            Stat::make('Shipped Orders', Order::where('status', 'shipped')->count())
                ->description('Out for delivery')
                ->descriptionIcon('heroicon-m-truck')
                ->color('purple')
                ->chart([5, 7, 9, 11, 13, 15, 17]),

            Stat::make('Delivered Orders', Order::where('status', 'delivered')->count())
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-home')
                ->color('success')
                ->chart([8, 10, 12, 14, 16, 18, 20]),

            Stat::make('This Month Revenue', '$' . number_format(Order::where('status', '!=', 'cancelled')
                ->where('status', '!=', 'refunded')
                ->whereMonth('created_at', $thisMonth)
                ->sum('total'), 2))
                ->description('Revenue this month')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([1000, 1500, 2000, 2500, 3000, 3500, 4000]),
            Stat::make('Today\'s Revenue', '$' . number_format(Order::where('status', '!=', 'cancelled')
                ->where('status', '!=', 'refunded')
                ->whereDate('created_at', $thisDay)
                ->sum('total'), 2))
                ->description('Revenue this day')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([1000, 1500, 3444, 2500, 2300, 12300, 4000]),

            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray')
                ->chart([2, 1, 3, 2, 4, 3, 5]),

            Stat::make('Cancelled Orders', Order::where('status', 'cancelled')->count())
                ->description('Cancelled orders')
                ->descriptionIcon('heroicon-m-x-mark')
                ->color('danger')
                ->chart([1, 2, 1, 3, 2, 4, 3]),
        ];
    }
} 
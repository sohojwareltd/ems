<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        try {
            $user = Auth::user();
            return $user && $user->can('dashboard.view');
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getStats(): array
    {
        // Get current month data
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        // Current month stats
        $currentMonthSales = Order::where('created_at', '>=', $currentMonth)->sum('total');
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonth)->count();
        $currentMonthCustomers = User::where('created_at', '>=', $currentMonth)
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            })->count();
        
        // Last month stats for comparison
        $lastMonthSales = Order::whereBetween('created_at', [$lastMonth, $currentMonth])->sum('total');
        $lastMonthOrders = Order::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        $lastMonthCustomers = User::whereBetween('created_at', [$lastMonth, $currentMonth])
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            })->count();
        
        // Calculate percentage changes
        $salesChange = $lastMonthSales > 0 ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;
        $ordersChange = $lastMonthOrders > 0 ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;
        $customersChange = $lastMonthCustomers > 0 ? (($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 : 0;
        
        return [
            Stat::make('Total Sales', '$' . number_format($currentMonthSales, 2))
                ->description('This month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($salesChange >= 0 ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Total Orders', number_format($currentMonthOrders))
                ->description('This month')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($ordersChange >= 0 ? 'success' : 'danger')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('New Customers', number_format($currentMonthCustomers))
                ->description('This month')
                ->descriptionIcon('heroicon-m-users')
                ->color($customersChange >= 0 ? 'success' : 'danger')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Total Products', Product::count())
                ->description('Active products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
} 
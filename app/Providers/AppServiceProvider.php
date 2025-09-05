<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Pagination\Paginator;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Cart Service
        $this->app->singleton('cart', function ($app) {
            return new CartService();
        });

        // Register Checkout Service
        $this->app->singleton('checkout', function ($app) {
            return new CheckoutService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // Register the Order observer
        Order::observe(OrderObserver::class);
    }
}

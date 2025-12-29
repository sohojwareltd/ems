<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'product.has.purchased' => \App\Http\Middleware\ProductHasPurchased::class,
            'check.device.session' => \App\Http\Middleware\CheckDeviceSession::class,
        ]);
        
        // Apply device check middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\CheckDeviceSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

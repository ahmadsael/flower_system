<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\FarmerAuthMiddleware;
use App\Http\Middleware\CustomerAuthMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

                Route::middleware('web')
                ->prefix('farmer')
                ->name('farmer.')
                ->group(base_path('routes/farmer.php'));

                Route::middleware('web')
                ->prefix('customer')
                ->name('customer.')
                ->group(base_path('routes/customer.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => AdminAuthMiddleware::class,
            'farmer.auth' => FarmerAuthMiddleware::class,
            'customer.auth' => CustomerAuthMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

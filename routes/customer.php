<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Auth\AuthController;
use App\Http\Controllers\Customer\Cart\CartController;
use App\Http\Controllers\Customer\Order\OrderController;
use App\Http\Controllers\Customer\Favorite\FavoriteController;

Route::get('/login', [AuthController::class, 'loginPage'])->name('login.page');
Route::get('/register', [AuthController::class, 'registerPage'])->name('register.page');
Route::post('/login/check', [AuthController::class, 'login'])->name('login');
Route::post('/register/store', [AuthController::class, 'register'])->name('register');

Route::middleware('customer.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/checkout/place', [OrderController::class, 'place'])->name('orders.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
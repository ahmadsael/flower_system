<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Farmer\Auth\AuthController;
use App\Http\Controllers\Farmer\FarmerController;
use App\Http\Controllers\Farmer\Profile\ProfileController;
use App\Http\Controllers\Farmer\Product\ProductController;
use App\Http\Controllers\Farmer\Order\OrderController;
use App\Http\Controllers\Farmer\Wallet\WalletController;

Route::get('/login' , [AuthController::class , 'loginPage'])->name('login.page');

Route::post('/login/check' , [AuthController::class , 'login'])->name('login');

Route::group(['middleware' => ['farmer.auth']], function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    //=================================== Dashboard Route =============================

    Route::get('/dashboard', [FarmerController::class, 'index'])->name('dashboard');

     //=================================== Profile Routes =============================
    
     Route::group(['prefix' => 'profile', 'as' => 'profile.', 'controller' => ProfileController::class], function () {
        
        Route::get('/', 'index')->name('index');

        Route::put('/update', 'update')->name('update');

        Route::put('/update-password', 'updatePassword')->name('update.password');
    });

       //====================================== Product Route ==================================

       Route::group(['prefix' => 'product', 'as' => 'product.', 'controller' => ProductController::class], function () {

        Route::get('/index', 'index')->name('index');

        Route::post('/store', 'store')->name('store');

        Route::get('/edit/{id}', 'edit')->name('edit');

        Route::put('/update/{id}', 'update')->name('update');

        Route::delete('/delete/{id}', 'delete')->name('delete');

        Route::put('/update/password/{id}', 'updatePassword')->name('update.password');

        Route::get('/export', 'export')->name('export');

    });

       //====================================== Order Route ==================================

       Route::group(['prefix' => 'order', 'as' => 'order.', 'controller' => OrderController::class], function () {

        Route::get('/index', 'index')->name('index');

        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/accept/{id}', 'accept')->name('accept');

        Route::post('/reject/{id}', 'reject')->name('reject');

        Route::put('/update-status/{id}', 'updateStatus')->name('updateStatus');

        Route::get('/export', 'export')->name('export');

    });

       //====================================== Wallet Route ==================================

       Route::group(['prefix' => 'wallet', 'as' => 'wallet.', 'controller' => WalletController::class], function () {

        Route::get('/index', 'index')->name('index');

        Route::post('/request-withdrawal', 'requestWithdrawal')->name('requestWithdrawal');

        Route::get('/export', 'export')->name('export');

    });

});
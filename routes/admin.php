<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\Admin\Role\RoleController;
use App\Http\Controllers\Admin\Farmer\FarmerController;
use App\Http\Controllers\Admin\Farmer\WithdrawalController;
use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Category\CategoryController;


Route::get('/login' , [AuthController::class , 'loginPage'])->name('login.page');

Route::post('/login/check' , [AuthController::class , 'login'])->name('login');

Route::group(['middleware' => ['admin.auth']], function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    //=================================== Dashboard Route =============================

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    //=================================== Profile Routes =============================
    
    Route::group(['prefix' => 'profile', 'as' => 'profile.', 'controller' => ProfileController::class], function () {
        
        Route::get('/', 'index')->name('index');

        Route::put('/update', 'update')->name('update');

        Route::put('/update-password', 'updatePassword')->name('update.password');
    });


     //=================================== Admin Management Routes =============================

     Route::group(['prefix' => 'admin', 'as' => 'admin.', 'controller' => AdminAdminController::class], function () {

        Route::get('/index', 'index')->name('index');

        Route::post('/store', 'store')->name('store');

        Route::get('/edit/{id}', 'edit')->name('edit');

        Route::put('/update/{id}', 'update')->name('update');

        Route::delete('/delete/{id}', 'delete')->name('delete');

        Route::put('/update/password/{id}', 'updatePassword')->name('update.password');

        Route::get('/export', 'export')->name('export');

        Route::group(['prefix' => 'role' , 'as' => 'role.' , 'controller' => RoleController::class] , function () {

            Route::get('/index', 'index')->name('index');

            Route::post('/store', 'store')->name('store');

            Route::get('/edit/{id}', 'edit')->name('edit');

            Route::put('/update/{id}', 'update')->name('update');

            Route::delete('/delete/{id}', 'delete')->name('delete');

            Route::get('/export', 'export')->name('export');
        });

    });

    //======================================Farmer Route ==================================

    Route::group(['prefix' => 'farmer', 'as' => 'farmer.', 'controller' => FarmerController::class], function () {

        Route::get('/index', 'index')->name('index');

        Route::post('/store', 'store')->name('store');

        Route::get('/edit/{id}', 'edit')->name('edit');

        Route::put('/update/{id}', 'update')->name('update');

        Route::delete('/delete/{id}', 'delete')->name('delete');

        Route::put('/update/password/{id}', 'updatePassword')->name('update.password');

        Route::get('/export', 'export')->name('export');

        Route::group(['prefix' => 'withdrawal', 'as' => 'withdrawal.', 'controller' => WithdrawalController::class], function () {

            Route::get('/index', 'index')->name('index');

            Route::post('/approve/{id}', 'approve')->name('approve');

            Route::post('/reject/{id}', 'reject')->name('reject');

            Route::put('/update-status/{id}', 'updateStatus')->name('updateStatus');

            Route::get('/export', 'export')->name('export');

        });

    });

     //======================================Customer Route ==================================

     Route::group(['prefix' => 'customer', 'as' => 'customer.', 'controller' => CustomerController::class], function () {

        Route::get('/index', 'index')->name('index');

        Route::post('/store', 'store')->name('store');

        Route::get('/edit/{id}', 'edit')->name('edit');

        Route::put('/update/{id}', 'update')->name('update');

        Route::delete('/delete/{id}', 'delete')->name('delete');

        Route::put('/update/password/{id}', 'updatePassword')->name('update.password');

        Route::get('/export', 'export')->name('export');
        
    });

         //======================================Category Route ==================================

         Route::group(['prefix' => 'category', 'as' => 'category.', 'controller' => CategoryController::class], function () {

            Route::get('/index', 'index')->name('index');
    
            Route::post('/store', 'store')->name('store');
    
            Route::get('/edit/{id}', 'edit')->name('edit');
    
            Route::put('/update/{id}', 'update')->name('update');
    
            Route::delete('/delete/{id}', 'delete')->name('delete');
    
            Route::put('/update/password/{id}', 'updatePassword')->name('update.password');
    
            Route::get('/export', 'export')->name('export');
            
        });

});
<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Modules\Order\Http\Controllers\BankController;
use App\Modules\Order\Http\Controllers\BasketController;
use App\Modules\Order\Http\Controllers\DiscountController;
use App\Modules\Order\Http\Controllers\OrderController;
use App\Modules\Order\Http\Controllers\OrderShippingStatusController;
use App\Modules\Order\Http\Controllers\ShippingMethodController;

Route::middleware('AdminPermission')->group(function() {
    Route::controller(BankController::class)
        ->prefix('admin/bank')
        ->name('admin.bank.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/sort/', 'updateSort')->name('sort')->withoutMiddleware('AdminPermission');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
        });
    Route::controller(OrderShippingStatusController::class)
        ->prefix('admin/order-shipping-status')
        ->name('admin.order-shipping-status.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });
    Route::controller(ShippingMethodController::class)
        ->prefix('admin/shipping-method')
        ->name('admin.shipping-method.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
            Route::get('/chapar-branches', 'chaparBranch')->name('chapar-branch')->withoutMiddleware('AdminPermission');
            Route::get('/sql-to-json', 'sqlToJson')->name('sql-to-json')->withoutMiddleware('AdminPermission');
        });
    Route::controller(DiscountController::class)
        ->prefix('admin/discount')
        ->name('admin.discount.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });
    Route::controller(BasketController::class)
    ->prefix('admin/basket')
    ->name('admin.basket.')
    ->group(function () {
        Route::get('/get-users', 'getUsers')->name('get-users');
        Route::get('/get-cities', 'getCities')->name('get-cities');
        Route::get('/get-products', 'getProducts')->name('get-products');
        Route::get('/', 'index')->name('index');
        Route::get('/detail/{id}', 'detail')->name('detail');
        Route::get('/export', 'export')->name('export')->withoutMiddleware('AdminPermission');
    });
    Route::controller(OrderController::class)
        ->prefix('admin/order')
        ->name('admin.order.')
        ->group(function () {
            Route::get('/convert-dates', 'convertDates')->name('convert-dates')->withoutMiddleware('AdminPermission');
            Route::get('/', 'index')->name('index');
            Route::post('/change-shipping-status/{id}', 'changeShippingStatus')->name('change-shipping-status');
            Route::post('/post-code/', 'postCode')->name('post-code')->withoutMiddleware('AdminPermission');
            Route::post('/bijak/{id}', 'uploadBijak')->name('bijak');
            Route::get('/detail/{id}', 'detail')->name('detail');
            Route::get('/delete/{id}', 'destroy')->name('delete');
            Route::get('/factor/{id}', 'factor')->name('factor');
            Route::get('/post-label/{id}', 'postLabel')->name('post-label')->withoutMiddleware('AdminPermission');
            Route::post('/return/{id}', 'return')->name('return');
            Route::get('/order-return/{id}', 'orderReturn')->name('order-return');
            Route::get('/image-accept/{id}', 'imageAccept')->name('image-accept')->withoutMiddleware('AdminPermission');
            Route::get('/image-decline/{id}', 'imageDecline')->name('image-decline')->withoutMiddleware('AdminPermission');
            Route::get('/export', 'export')->name('export')->withoutMiddleware('AdminPermission');
        });
});

Route::get('/inquire-zarinpal-payments/{token}', [OrderController::class, 'inquireZarinpalPayments'])
    ->middleware('throttle:5,1')
    ->name('order.inquire-zarinpal-payments');



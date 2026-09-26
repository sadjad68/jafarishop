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

use App\Modules\Blog\Http\Controllers\BlogCategoryController;
use App\Modules\Blog\Http\Controllers\BlogController;
use App\Modules\Location\Http\Controllers\LocationController;

Route::middleware('AdminPermission')->group(function() {
Route::controller(LocationController::class)
    ->prefix('admin/state')
    ->name('admin.state.')
    ->group(function () {
        Route::get('/', 'getState')->name('index');
        Route::get('/change-status/{id}', 'changeStateStatus')->name('change-status');
    });
Route::controller(LocationController::class)
    ->prefix('admin/city')
    ->name('admin.city.')
    ->group(function () {
        Route::get('/', 'getCity')->name('index');
        Route::get('/change-status/{id}', 'changeCityStatus')->name('change-status');
    });
    Route::controller(LocationController::class)
        ->prefix('admin/address')
        ->name('admin.address.')
        ->group(function () {
            Route::get('/', 'getAddress')->name('index');
        });
});

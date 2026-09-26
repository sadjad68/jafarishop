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

use App\Modules\Setting\Http\Controllers\PartialController;
use App\Modules\Setting\Http\Controllers\SloganController;
use App\Modules\Setting\Http\Controllers\ThemeController;
use App\Modules\Setting\Http\Controllers\SettingController;
use App\Modules\Setting\Http\Controllers\BranchController;
use App\Modules\Setting\Http\Controllers\SocialController;

Route::middleware('AdminPermission')->group(function() {
Route::controller(SocialController::class)
    ->prefix('admin/social')
    ->name('admin.social.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});


Route::middleware('AdminPermission')->group(function() {
Route::controller(BranchController::class)
    ->prefix('admin/branch')
    ->name('admin.branch.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});


//general
Route::middleware('AdminPermission')->prefix('admin/setting')->name('admin.setting.')->group(function () {
    Route::controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/edit', 'update')->name('edit');
        Route::get('/clear-cache', 'clearCache')->name('clear-cache');

    });
//theme
});
Route::middleware('AdminPermission')->prefix('admin/theme')->name('admin.theme.')->group(function () {
    Route::controller(ThemeController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/edit', 'update')->name('edit');

    });


});
//slogan
Route::middleware('AdminPermission')->prefix('admin/slogan')->name('admin.slogan.')->group(function () {
    Route::controller(SloganController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');

    });
});
//developer
Route::middleware('AdminPermission')->prefix('admin/setting-partial')->name('admin.setting-partial.')->group(function () {
    Route::controller(PartialController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('add');
        Route::post('/create', 'store')->name('add');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::get('/delete-root/{id}', 'deleteRoot')->name('delete-root');
    });
});
//sitemap
Route::middleware('AdminPermission')->prefix('admin/sitemap')->name('admin.sitemap.')->group(function () {
    Route::controller(\App\Modules\Setting\Http\Controllers\SitemapController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/edit', 'update')->name('edit');
    });
});

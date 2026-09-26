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

use App\Modules\Seo\Http\Controllers\CanonicalController;
use App\Modules\Seo\Http\Controllers\RedirectController;
use \App\Modules\Seo\Http\Controllers\SeoController;

Route::middleware('AdminPermission')->prefix('admin')->group(function () {
    Route::name('admin.seo.')->prefix('seo')->group(function () {
        Route::controller(SeoController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/index-all', 'indexAll')->name('index-all');
        });
    });

    Route::name('admin.redirect.')->prefix('redirect')->group(function () {
        Route::controller(RedirectController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/delete/{id}', 'destroy')->name('delete');
            Route::post('/import', 'import')->name('import')->withoutMiddleware('AdminPermission');
        });
    });

    Route::name('admin.canonical.')->prefix('canonical')->group(function () {
        Route::controller(CanonicalController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });
    });
});


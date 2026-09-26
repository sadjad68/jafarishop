<?php

use App\Modules\Banner\Http\Controllers\BannerController;
use App\Modules\Banner\Http\Controllers\HighlightController;
Route::middleware('AdminPermission')->group(function () {
Route::prefix('admin/banner')->name('admin.banner.')->group(function() {
    Route::controller(BannerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::get('/sort/', 'sort')->name('sort');
        Route::post('/sort/', 'updateSort')->name('sort');
    });
});
Route::prefix('admin/highlight')->name('admin.highlight.')->group(function() {
    Route::controller(HighlightController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});
});

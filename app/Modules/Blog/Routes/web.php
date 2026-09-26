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

Route::middleware('AdminPermission')->group(function() {
Route::controller(BlogController::class)
    ->prefix('admin/blog')
    ->name('admin.blog.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/test', 'test')->name('test')->withoutMiddleware('AdminPermission');
        Route::post('/create', 'store')->name('create');
        Route::post('/test-create', 'storeTest')->name('test-create')->withoutMiddleware('AdminPermission');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
Route::controller(BlogCategoryController::class)
    ->prefix('admin/blog-category')
    ->name('admin.blog-category.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});

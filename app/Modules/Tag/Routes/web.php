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


use App\Modules\Tag\Http\Controllers\TagController;

Route::middleware('AdminPermission')->group(function() {
Route::controller(TagController::class)
    ->prefix('/admin/tag')
    ->name('admin.tag.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::get('/sort', 'sort')->name('sort');
        Route::post('/sort', 'updateSort')->name('sort');
    });

});

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

use App\Modules\Comment\Http\Controllers\CommentController;

Route::middleware('AdminPermission')->prefix('admin/comment')->name('admin.comment.')->group(function () {
    Route::controller(CommentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::post('/reply', 'reply')->name('reply')->withoutMiddleware('AdminPermission');
        Route::get('/edit-status/{id}', 'updateStatus')->name('edit-status');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});

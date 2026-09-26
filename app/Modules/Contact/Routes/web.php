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

use App\Modules\Contact\Http\Controllers\ContactController;

Route::middleware('AdminPermission')->prefix('admin/contact')->name('admin.contact.')->group(function () {
    Route::controller(ContactController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/status/{id}', 'changeStatus')->name('status');
        Route::get('/delete/{id}', 'delete')->name('delete');

    });

});

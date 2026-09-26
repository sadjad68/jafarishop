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

use App\Modules\Course\Http\Controllers\CourseCategoryController;
use App\Modules\Course\Http\Controllers\CourseController;
use App\Modules\Course\Http\Controllers\SessionController;
use App\Modules\Course\Http\Controllers\SessionFileController;

Route::middleware('AdminPermission')->prefix('admin/session')->name('admin.session.')->group(function() {
    Route::controller(SessionController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('add');
        Route::post('/create', 'store')->name('add');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::post('/update-order', 'updateOrder')->name('order');
    });
});
Route::middleware('AdminPermission')->prefix('admin/session-file')->name('admin.session-file.')->group(function() {
    Route::controller(SessionFileController::class)->group(function () {
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});


Route::middleware('AdminPermission')->prefix('admin/course')->name('admin.course.')->group(function() {
    Route::controller(CourseController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('add');
        Route::post('/create', 'store')->name('add');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});
Route::middleware('AdminPermission')->prefix('admin/course-category')->name('admin.course-category.')->group(function() {
    Route::controller(CourseCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('add');
        Route::post('/create', 'store')->name('add');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
       });

});

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

use App\Modules\Service\Http\Controllers\FaqController;
use App\Modules\Service\Http\Controllers\ServiceController;
use App\Modules\Service\Http\Controllers\FeeController;
use App\Modules\Service\Http\Controllers\PackageController;
use App\Modules\Service\Http\Controllers\ServiceRequestController;
use App\Modules\Service\Http\Controllers\WorkSampleController;

Route::middleware('AdminPermission')->group(function () {
    Route::controller(WorkSampleController::class)
        ->prefix('admin/worksample')
        ->name('admin.worksample.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create/', 'create')->name('create');
            Route::post('/create/', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
            Route::get('/image/{id}', 'images')->name('image');
            Route::get('/thumbnail/{id}', 'thumbnail')->name('thumbnail');
            Route::get('/delete-image/{id}', 'deleteImage')->name('delete-image');
            Route::post('/create-image/{id}', 'createImage')->name('create-image');
        });


    Route::controller(PackageController::class)
        ->prefix('admin/package')
        ->name('admin.package.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });


    Route::controller(FeeController::class)
        ->prefix('admin/fee')
        ->name('admin.fee.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/edit/{id}', 'update')->name('edit');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });

    Route::controller(ServiceController::class)->prefix('admin/service')->name('admin.service.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::get('/delete-root/{id}', 'deleteRoot')->name('delete-root');
        Route::get('/sort/{parent_id?}', 'sort')->name('sort');
        Route::post('/sort/{parent_id?}', 'updateSort')->name('sort');

    });
    Route::controller(FaqController::class)->prefix('admin/service/faq')->name('admin.service-faq.')->withoutMiddleware('AdminPermission')->group(function () {
            Route::get('/vue', 'faqs')->name('list');
            Route::post('/create', 'store')->name('create');
            Route::get('/delete-faq/{id?}', 'destroyFaq')->name('delete-faq');
            Route::get('/{id}', 'index')->name('index');
        });
    Route::controller(ServiceRequestController::class)->prefix('admin/service-request')->name('admin.service-request.')->group(function () {
            Route::get('/requests', 'index')->name('index');
            Route::get('/show/{id}', 'show')->name('show');
            Route::delete('/destroy/{id}', 'destroy')->name('delete');

        });
});

<?php

use App\Modules\Service\Http\Controllers\Api\V1\PackageController;
use App\Modules\Service\Http\Controllers\Api\V1\SampleController;
use App\Modules\Service\Http\Controllers\Api\V1\ServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */


Route::name('api.')->group(function () {
    Route::get('/v1/packages', [PackageController::class, "getList"])->name('package-list');
    Route::get('/v1/package/{url}', [PackageController::class, "getDetail"])->name('package-detail');
});

Route::name('api.')->group(function () {
    Route::get('/v1/portfolios', [SampleController::class, "getIndex"])->name('sample-list');
    Route::get('/v1/portfolio-filters', [SampleController::class, "getServiceForFilter"])->name('portfolio-filters');
    Route::post('/v1/portfolio-vue', [SampleController::class, "getListForVue"])->name('portfolio-vue');
    Route::get('/v1/portfolio/{url}', [SampleController::class, "getDetail"])->name('sample-detail');
});

Route::name('api.')->group(function () {
    Route::get('/v1/services', [ServiceController::class, "getIndex"])->name('main-services');
    Route::get('/v1/service/{url}', [ServiceController::class, "getDetail"])->name('service-list');
});

<?php

use App\Modules\General\Http\Controllers\Api\V1\FirstPageController;
use App\Modules\General\Http\Controllers\Api\V1\LayoutController;
use App\Modules\General\Http\Controllers\Api\V1\SearchController;
use App\Modules\General\Http\Controllers\Api\PrerequisiteController;


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

    Route::get('/v1/first-page', [FirstPageController::class, "getFirstPageData"])->name('first-page');
    Route::get('/v1/setting', [LayoutController::class, "getLayoutData"])->name('setting');
    Route::get('/v1/search-api', [SearchController::class, "getData"])->name('search');
});

Route::prefix('admin/')->name("api.admin.")->middleware(['auth:admin_jwt','api-permission'])->group(function () {

    Route::prefix('prerequisite/')->controller(PrerequisiteController::class)->name('prerequisite.')->group(function () {
        Route::get('/brands', 'brands')->name("brands");
        Route::get('/tags', 'tags')->name("tags");
        Route::get('/services', 'services')->name("services");
    });

});

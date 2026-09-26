<?php

use App\Modules\Seo\Http\Controllers\Api\V1\CanonicalController;
use App\Modules\Seo\Http\Controllers\Api\V1\SeoController;
use App\Modules\Seo\Http\Controllers\Api\V1\RedirectController;

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
    Route::get('/v1/redirect', [RedirectController::class, "index"])->name('redirect.index');
    Route::get('/v1/canonical', [CanonicalController::class, "index"])->name('canonical.index');

    Route::get('/v1/seo/{url}', [SeoController::class, "index"])->name('seo.index');

});

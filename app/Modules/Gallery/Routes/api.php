<?php

use App\Modules\Gallery\Http\Controllers\Api\V1\GalleryController;
use Illuminate\Http\Request;

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
    Route::get('/v1/galleries', [GalleryController::class, "category"])->name('category');
    Route::get('/v1/gallery/{url}', [GalleryController::class, "getList"])->name('gallery');
});

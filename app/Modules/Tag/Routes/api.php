<?php

use App\Modules\Tag\Http\Controllers\V1\TagController;
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
    Route::get('/v1/tags', [TagController::class, "getIndex"])->name('tag-list');
    Route::get('/v1/tag/{url}', [TagController::class, "getDetail"])->name('tag-detail');
});


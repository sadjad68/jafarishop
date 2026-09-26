<?php

use App\Modules\Comment\Http\Controllers\Api\V1\CommentController;
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
    Route::post('/v1/post-comment', [CommentController::class, "create"])->name('post-comment');
    Route::post('/product-comment-store', [App\Modules\Comment\Http\Controllers\CommentController::class, "storeProduct"])->name('store-product');
});

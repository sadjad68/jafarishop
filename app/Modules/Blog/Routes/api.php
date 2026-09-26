<?php

use Illuminate\Http\Request;
use App\Modules\Blog\Http\Controllers\Api\BlogCategoryController as ApiBlogCategoryController;
use App\Modules\Blog\Http\Controllers\Api\BlogController as ApiBlogController;
use App\Modules\Blog\Http\Controllers\Api\V1\BlogController;
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
    Route::get('/v1/blogs', [BlogController::class, "getIndex"])->name('blogs');
    Route::get('/v1/blogs/{url}', [BlogController::class, "getList"])->name('blog-list');
    Route::get('/v1/blog/{url}', [BlogController::class, "getDetail"])->name('blog-detail');
});

Route::prefix('admin/')->name("api.admin.")->middleware(['auth:admin_jwt', 'api-permission'])->group(function () {
    Route::prefix('blog-category/')->controller(ApiBlogCategoryController::class)->name('blog-category.')->group(function () {
        Route::get('/', 'index')->name("index");
        Route::post('/add', 'store')->name("store");
        Route::get('/show/{id}', 'show')->name("show");
        Route::put('/edit/{id}', 'update')->name("update");
    });
    Route::prefix('blog/')->controller(ApiBlogController::class)->name('blog.')->group(function () {
        Route::get('/', 'index')->name("index");
        Route::post('/add', 'store')->name("store");
        Route::get('/show/{id}', 'show')->name("show");
        Route::put('/edit/{id}', 'update')->name("update");
    });
});

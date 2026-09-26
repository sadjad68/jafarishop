<?php

use App\Modules\Product\Http\Controllers\Api\V1\BrandController;
use App\Modules\Product\Http\Controllers\Api\V1\ProductCategoryController;
use App\Modules\Product\Http\Controllers\Api\V1\ProductController;

use App\Modules\Product\Http\Controllers\Api\ProductController as ApiProductController;
use App\Modules\Product\Http\Controllers\Api\ImageController as ApiImageController;
use App\Modules\Product\Http\Controllers\Api\SpecificationController as ApiSpecificationController;
use App\Modules\Product\Http\Controllers\Api\ProductCategoryController as ApiProductCategoryController;

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
    //cat
    Route::get('/v1/categories', [ProductCategoryController::class, "getList"])->name('category-list');
    Route::get('/v1/category/{url}', [ProductCategoryController::class, "getProductList"])->name('product-list');
    Route::post('/v1/product-cat-vue/', [ProductCategoryController::class, "getProductListVue"])->name('product-list-vue');
    //brand
    Route::get('/v1/brands', [BrandController::class, "getList"])->name('brand-list');
    Route::get('/v1/brand/{url}', [BrandController::class, "getProductList"])->name('brand-detail');
    Route::post('/v1/product-brand-vue/', [BrandController::class, "getProductListVue"])->name('product-brand-vue');
    //product
    Route::get('/v1/product/{url}', [ProductController::class, "getProductDetail"])->name('product-detail');
    Route::get('/v1/discounted-products', [ProductController::class, "getDiscountedProductTest"])->name('discounted-products');
    //all
    Route::post('/v1/all-product-vue/', [ProductCategoryController::class, "getAllProductVue"])->name('all-product-list-vue');

});
Route::prefix('admin/')->name("api.admin.")->middleware(['auth:admin_jwt', 'api-permission'])->group(function () {
    Route::prefix('product-category/')->controller(ApiProductCategoryController::class)->name('product-category.')->group(function () {
        Route::get('/', 'index')->name("index");
        Route::get('/show/{id}', 'show')->name("show");
        Route::post('/add', 'store')->name("store");
        Route::put('/edit/{id}', 'update')->name("update");
    });
    Route::prefix('product/')->controller(ApiProductController::class)->name('product.')->group(function () {
        Route::get('/', 'index')->name("index");
        Route::get('/show/{id}', 'show')->name("show");
        Route::post('/add', 'store')->name("store");
        Route::put('/edit/{id}', 'update')->name("update");
        Route::post('timer/{id}', 'timer')->name("timer");
        Route::put('specification/select/{id}', 'specificationSelect')->name("specification-select");
        Route::put('specification/text/{id}', 'specificationText')->name("specification-text");
    });
    Route::controller(ApiImageController::class)->prefix('product/image/')->name('product-image.')->group(function () {
        Route::post('/create/{id}', 'create')->name('create');
        Route::post('/add-variants/{id}', 'addVariants')->name('add-variants');
        Route::post('/set-thumbnail/{id}', 'setImageThumbnail')->name('set-thumbnail');
    });
    Route::prefix('specification/')->controller(ApiSpecificationController::class)->name('specification.')->group(function () {
        Route::get('products/{id}', 'products')->name("products");
        Route::get('/', 'index')->name("index");
        Route::get('{id}/values', 'values')->name("values");
    });
});

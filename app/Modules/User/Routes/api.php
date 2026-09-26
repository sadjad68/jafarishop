<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Http\Controllers\Api\AdminAuthController;
use App\Modules\User\Http\Controllers\Api\UserController;

Route::prefix('v1/users')->group(function (){
   Route::get('', [UserController::class, 'users']);
});
Route::prefix('/admin')->middleware('api')->name('api.auth.')->group(function (){
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::middleware(['auth:admin_jwt','api-permission'])->group(function () {
        Route::get('/me', [AdminAuthController::class, 'me'])->name("me");
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name("logout");
        Route::post('/refresh', [AdminAuthController::class, 'refresh'])->name("refresh");
    });

    Route::prefix('users/')->controller(UserController::class)->name('user.')->group(function (){
        Route::get('/', 'index')->name("index");
        Route::post('/add', 'create')->name("create");
        Route::post('/edit/{id}', 'edit')->name("edit");
        Route::get('/delete/{id}', 'delete')->name("delete");
    });
 });

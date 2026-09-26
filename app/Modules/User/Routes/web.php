<?php

use App\Modules\User\Http\Controllers\AuthController;
use App\Modules\User\Http\Controllers\PermissionController;
use App\Modules\User\Http\Controllers\UserController;

Route::prefix('admin')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('admin.login');
        Route::get('/logout', 'logout')->name('admin.logout');
        Route::post('/post-login', 'postLogin');
        Route::get('/change-password', 'showChangePasswordForm')->name('admin.change-password');
        Route::post('/change-password/send-code', 'sendPasswordResetCode')
            ->middleware('throttle:3,1')
            ->name('admin.change-password.send-code');
        Route::get('/change-password/verify', 'showVerifyCodeForm')
            ->name('admin.change-password.verify');
        Route::post('/change-password/verify', 'verifyPasswordResetCode')
            ->middleware('throttle:5,1')
            ->name('admin.change-password.verify.post');
        Route::get('/change-password/reset', 'showResetPasswordForm')
            ->name('admin.change-password.reset');
        Route::post('/change-password/reset', 'resetPassword')
            ->middleware('throttle:3,1')
            ->name('admin.change-password.reset.post');

    });
});

Route::middleware('AdminPermission')->group(function () {
Route::controller(UserController::class)
    ->prefix('admin/user')->name('admin.user.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('edit');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::patch('/change-password/{id}', 'changePassword')->name('change-password')->withoutMiddleware('AdminPermission');
        Route::get('/export', 'export')->name('export')->withoutMiddleware('AdminPermission');
    });

Route::controller(PermissionController::class)
    ->prefix('admin/permission')->name('admin.permission.')
    ->group(function () {
        Route::get('/', 'getPermission')->name('index');
        Route::get('/add', 'getAddPermission')->name('add');
        Route::post('/add', 'postAddPermission')->name('add');
        Route::get('/edit/{id}', 'getEditPermission')->name('edit');
        Route::post('/edit/{id}', 'postEditPermission')->name('edit');
        Route::get('/delete/{id}', 'getDeletePermission')->name('delete');
    });

});

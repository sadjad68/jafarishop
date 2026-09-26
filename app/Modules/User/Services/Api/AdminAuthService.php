<?php

namespace App\Modules\User\Services\Api;

use App\Models\ApiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AdminAuthService
{
    // =======================
    // ورود
    // =======================
    public function login(array $credentials)
    {
        if (! $token = auth('admin_jwt')->attempt($credentials)) {
            return ['error' => 'ایمیل یا رمز اشتباهه', 'status' => 401];
        }

        $user = auth('admin_jwt')->user();

        if (! $user->roles()->exists()) {
            return ['error' => 'حساب شما دسترسی لازم را ندارد', 'status' => 403];
        }
        return $this->respondWithToken($token);
    }

    // =======================
    // خروج
    // =======================
    public function logout()
    {
        auth('admin_jwt')->logout();
        return ['message' => 'با موفقیت خارج شدی'];
    }

    // =======================
    // رفرش توکن
    // =======================
    public function refresh()
    {
        return $this->respondWithToken(auth('admin_jwt')->refresh());
    }

    // =======================
    // اطلاعات کاربر لاگین شده
    // =======================
    public function me()
    {
        return auth('admin_jwt')->user();
    }

    // =======================
    // پاسخ با توکن
    // =======================
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('admin_jwt')->factory()->getTTL() * 60
        ];
    }
}

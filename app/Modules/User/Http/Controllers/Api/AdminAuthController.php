<?php

namespace App\Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\User\Services\Api\AdminAuthService;

class AdminAuthController extends Controller
{
    protected $authService;

    public function __construct(AdminAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json($result, $result['status'] ?? 200);
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }

    public function logout()
    {
        return response()->json($this->authService->logout());
    }

    public function refresh()
    {
        return response()->json($this->authService->refresh());
    }
}

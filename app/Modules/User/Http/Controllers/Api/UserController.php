<?php

namespace App\Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\User\DTO\UserApiDTO;
use App\Modules\User\DTO\UserDTO;
use App\Modules\User\Entities\User;
use App\Modules\User\Filters\UserFilter;
use App\Modules\User\Services\UserService;

class UserController extends Controller
{

    public function __construct(protected UserService $userService)
    {}
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->hasAny(['full_name', 'mobile', 'email'])) {
            $filters = [
                'full_name' => $request->input('full_name'),
                'mobile' => $request->input('mobile'),
                'email' => $request->input('email'),
            ];
            $query = app(UserFilter::class)->apply($query, $filters);
        }
        $user = $query->orderByDesc('id')->select(['id', 'full_name', 'mobile', 'email'])->paginate(20);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function create(Request $request)
    {
        $this->userService->createFromApi(UserApiDTO::fromRequest($request));
        CacheHelper::clearCache();
        return response()->json([
            'success' => true,
            'message' => "کاربر جدید با موفقیت اضافه شد."
        ]);
    }

    public function edit(int $id, Request $request)
    {
        $this->userService->updateFromApi($id, UserApiDTO::fromRequest($request));
        CacheHelper::clearCache();
        return response()->json([
            'success' => true,
            'message' => "کاربر با موفقیت ویرایش شد."
        ]);
    }

    public function delete(int $id)
    {
        $this->userService->destroy($id);
        CacheHelper::clearCache();
        return response()->json([
            'success' => true,
            'message' => "کاربر با موفقیت حذف شد."
        ]);
    }
}

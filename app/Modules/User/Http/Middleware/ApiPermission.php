<?php

namespace App\Modules\User\Http\Middleware;

use Closure;
use App\Modules\User\Entities\User;
use App\Modules\User\Entities\UserType;
use Route;

class ApiPermission
{
    public function handle($request, Closure $next)
    {
        $user = auth('admin_jwt')->user();

        if ($user) {
            $admin = UserType::where('user_id',$user->id)->where('type','Admin')->first();
            if ($admin){
                $roles = [];
                $user_roles = @$user->roles;
                if ($user_roles){
                    foreach ($user_roles as $role){
                        $permission = unserialize($role->permission);
                        if($permission){
                            $roles = array_merge($roles, $permission);
                        }
                    }
                }
                $api_list = \Config::get('site.api_permissions');
                $currentRoute = str_replace('api.admin.', '', Route::currentRouteName());
                $permissionRouteName = @$api_list[$currentRoute];
                if (in_array($permissionRouteName, $roles) || $permissionRouteName == 'free') {
                    return $next($request);
                }
            }

        }
        return response()->json([
            'error' => 'شما به این بخش دسترسی ندارید.'
        ], 403);
    }
}

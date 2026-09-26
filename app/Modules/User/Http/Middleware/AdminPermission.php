<?php
namespace App\Modules\User\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Route;
use App\Modules\User\Entities\User;
use App\Modules\User\Entities\UserType;


class AdminPermission
{

    public function __construct(Guard $auth)
    {

        $this->auth = $auth;

    }
    public function handle($request, Closure $next)
    {
        $segments = $request->segments();

        if ($this->auth->check()) {
            $admin = UserType::where('user_id',$this->auth->user()->id)->where('type','Admin')->first();
            if ($admin){
                $roles = [];
                $user = User::find(@$this->auth->user()->id);
                $user_roles = @$user->roles;
                if ($user_roles){
                    foreach ($user_roles as $role){
                        $permission = unserialize($role->permission);
                        if($permission){
                            $roles = array_merge($roles, $permission);
                        }
                    }
                }
                if ($segments[0] == config('site.admin')) {
                    if (in_array(Route::currentRouteName(), $roles)) {
                        return $next($request);
                    }
                }
            }

        }
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } elseif(!str_contains('api',Route::currentRouteName())) {
            if (Auth::check() and $admin) {
                return redirect('/admin')->with('error', 'شما به این بخش دسترسی ندارید.');
            } else {
                return redirect('/admin/login')->with('error', 'شما به این بخش دسترسی ندارید.');
            }
        }
    }

}

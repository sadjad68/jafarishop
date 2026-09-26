<?php

namespace App\Modules\General\Http\Middleware;


use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Modules\General\Helper\SiteHelper;

class MainMiddleware
{

    public function handle($request, Closure $next)
    {
        $site_name = @SiteHelper::getInformation()['site_name'];

        if (session()->get('custom_data') == null) {
                session()->put('custom_data', $site_name . strtotime(Carbon::now()));
            session()->save();
        }
        return $next($request);
    }
}

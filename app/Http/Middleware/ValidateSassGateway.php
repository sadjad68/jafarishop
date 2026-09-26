<?php

namespace App\Http\Middleware;

use App\Library\SiteHelper;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ValidateSassGateway
{
    public function handle($request, Closure $next)
    {
        if ($request->is('torob_api/*')) {
            SiteHelper::setSiteInformation(abortIfMissing: false);

            return $next($request);
        }

        SiteHelper::setSiteInformation();

        return $next($request);
    }
}

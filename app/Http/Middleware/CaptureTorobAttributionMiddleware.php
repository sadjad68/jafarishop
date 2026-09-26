<?php

namespace App\Http\Middleware;

use App\Services\Torob\TorobAttributionService;
use Closure;
use Illuminate\Http\Request;

class CaptureTorobAttributionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('torob_api/*')) {
            TorobAttributionService::handle($request);
        }
        return $next($request);
    }
}

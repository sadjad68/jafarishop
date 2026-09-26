<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class DomainLog
{
    public function handle($request, Closure $next)
    {
        $domain = $request->getHost();
        Log::withContext(['domain' => $domain]);
        return $next($request);
    }
}


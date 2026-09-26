<?php

namespace App\Http\Middleware;

use App\Services\Torob\TorobAuthException;
use App\Services\Torob\TorobTokenValidator;
use Closure;
use Illuminate\Http\Request;

class ValidateTorobToken
{
    public function __construct(
        private readonly TorobTokenValidator $validator,
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        if (!config('torob.enabled')) {
            return response()->json(['error' => 'Torob API is disabled'], 503);
        }

        try {
            $this->validator->validate($request);
        } catch (TorobAuthException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}

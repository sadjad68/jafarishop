<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/checkout/finish-saman',
        '/checkout/finish-sadad',
        '/checkout/finish-sadad',
        '/checkout/finish-snapp-pay',
        '/checkout/finish-saderat',
        '/checkout/finish-irandargah',
        '/checkout/finish-parsian',
        '/checkout/finish-aqayepardakht',
        '/checkout/finish-digipay',
        'torob_api/*',
    ];
}

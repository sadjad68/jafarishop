<?php

return [

    'enabled' => env('TOROB_API_ENABLED', true),

    'per_page' => (int) env('TOROB_API_PER_PAGE', 100),

    'timezone' => env('TOROB_API_TIMEZONE', 'Asia/Tehran'),

    'audience' => env('TOROB_API_AUDIENCE'),

    'public_key' => env('TOROB_PUBLIC_KEY', 'MCowBQYDK2VwAyEAt6Mu4T0pBORY11W+QeM35UsmLO3vsf+6yKpFDEImFk0='),

    'token_version' => env('TOROB_TOKEN_VERSION', '1'),

    'skip_auth' => env('TOROB_SKIP_AUTH', false),

    'attribution_lifetime_hours' => (int) env('TOROB_ATTRIBUTION_LIFETIME_HOURS', 168),

];

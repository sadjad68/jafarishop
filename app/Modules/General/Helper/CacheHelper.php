<?php

namespace App\Modules\General\Helper;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CacheHelper
{
    public static function clearCache()
    {
        Cache::flush();

        if (class_exists(\App\Providers\ViewServiceProvider::class)) {
            \App\Providers\ViewServiceProvider::clearCanonicalsCache();
            \App\Providers\ViewServiceProvider::clearLayoutCache();
        }

        $templateUrl = config('setting.template_url');
        if (! empty($templateUrl)) {
            try {
                Http::withoutVerifying()
                    ->timeout(5)
                    ->get(rtrim($templateUrl, '/') . '/purge-cache');
            } catch (\Throwable $e) {
                // best-effort: template may be the same app or unreachable
            }
        }

        return true;
    }
}

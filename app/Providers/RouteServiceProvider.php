<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Modules\Seo\Services\RedirectService;
use App\Modules\Setting\Services\SettingService;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot()
    {
        $redirect_address = $this->getRedirectAddress();
        $this->configureRateLimiting();

        if (Schema::hasTable('settings')) {

            $rout_settings = SettingService::getFormatSettings(); // متغیر را اینجا تعریف کنید

            $this->routes(function () use ($rout_settings) { // متغیر را به صورت پارامتر در اینجا استفاده کنید
                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));

                Route::middleware('web')
                    ->group(function () use ($rout_settings) { // اینجا متغیر را در گروه به اشتراک بگذارید
                        require base_path('routes/web.php');
                    });
            });

        }
        $this->handleRedirects($redirect_address);
    }


    protected function getRedirectAddress()
    {
        $site_name = strtolower(str_replace('www.', '', request()->getHost()));
        if (Schema::hasTable('redirects')) {
            if (Cache::has('redirect_address_' . $site_name)) {
                return Cache::get('redirect_address_' . $site_name);
            } else {
                $redirects = RedirectService::findAll();
                if ($redirects != null) {
                    Cache::put('redirect_address_' . $site_name, $redirects, now()->addDay());
                }

                return $redirects;
            }
        }
    }

    protected function handleRedirects($redirect_address)
    {
        $address = trim(urldecode(request()->path()), '/');
        if (@$redirect_address) {
            foreach ($redirect_address as $redirect) {
                $old_address = trim(urldecode($redirect['old_address']), '/');
                if (strcasecmp($address, $old_address) === 0) {
                    return redirect()->to(trim(urldecode($redirect['new_address'])), $redirect['type'])->send();
                }
            }
        }
    }


    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // محدودیت برای endpoint‌های فید محصولات (emall، zarebin، storeyab، torob)
        // ۳۰ درخواست در دقیقه به ازای هر IP — برای کرالرهای مجاز کافی است
        RateLimiter::for('crawler-endpoints', function (Request $request) {
            return Limit::perMinute(30)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    $retryAfter = (int) ($headers['Retry-After'] ?? 60);
                    return response()->json([
                        'error'       => 'rate_limit_exceeded',
                        'message'     => 'تعداد درخواست‌های شما از حد مجاز فراتر رفته است.',
                        'retry_after' => $retryAfter,
                        'limit'       => (int) ($headers['X-RateLimit-Limit'] ?? 30),
                        'reset_at'    => now()->addSeconds($retryAfter)->toIso8601String(),
                    ], 429, $headers);
                });
        });
    }
}

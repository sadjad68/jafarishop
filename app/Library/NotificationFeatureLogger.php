<?php

namespace App\Library;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * جستجو در لاگ‌ها: NOTIFICATION_FEATURE یا فایل storage/logs/notification-feature-*.log
 */
class NotificationFeatureLogger
{
    public const LOG_TAG = 'NOTIFICATION_FEATURE';

    public const CHANNEL = 'notification_feature';

    public static function logState(
        string $status,
        string $source,
        string $site_name,
        $value,
        ?string $detail = null
    ): void {
        $channel = Log::channel(self::CHANNEL);
        $url = request() ? request()->fullUrl() : '';
        $user_id = Auth::id();
        $ip = request() ? request()->ip() : '';

        $message = self::LOG_TAG . ' | status=' . $status . ' | source=' . $source
            . ' | site_name=' . $site_name
            . ' | active_notifications=' . (string) $value;

        $context = [
            'url' => $url,
            'user_id' => $user_id,
            'ip' => $ip,
        ];
        if ($detail !== null) {
            $context['detail'] = $detail;
        }

        if ($status === 'DISABLED') {
            $channel->warning($message, $context);
        } else {
            $channel->info($message, $context);
        }
    }

    /** وقتی مقدار ۱ از کش قبلی حفظ شد (تا فیچر غیرفعال نشود) */
    public static function logPreserved(string $site_name): void
    {
        Log::channel(self::CHANNEL)->info(
            self::LOG_TAG . ' | PRESERVED active_notifications=1 from previous cache (new value was missing or 0)',
            [
                'site_name' => $site_name,
                'url' => request() ? request()->fullUrl() : '',
                'user_id' => Auth::id(),
                'ip' => request() ? request()->ip() : '',
            ]
        );
    }

    /** وقتی getSettingData خطا داد و از کش قدیمی استفاده شد */
    public static function logStaleCacheFallback(string $site_name, string $exception_message): void
    {
        Log::channel(self::CHANNEL)->warning(
            self::LOG_TAG . ' | getSettingData failed, using stale layout cache',
            [
                'site_name' => $site_name,
                'exception' => $exception_message,
                'url' => request() ? request()->fullUrl() : '',
                'user_id' => Auth::id(),
                'ip' => request() ? request()->ip() : '',
            ]
        );
    }
}

<?php

namespace App\Library;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use App\Modules\Seo\Services\RedirectService;

class SiteHelper
{

    public static function getReadingTime($description)
    {

// طول متن را بدست می‌آوریم
        $text_length = strlen($description);

// میانگین سرعت خواندن فرد در دقیقه (به عنوان مثال 200 کاراکتر در دقیقه)
        $average_reading_speed = 1000;

// مدت زمان خواندن متن را محاسبه می‌کنیم (به صورت دقیقه)
        $reading_time_minutes = $text_length / $average_reading_speed;

// مدت زمان خواندن متن را گرد می‌کنیم به بالا
        $reading_time_minutes = ceil($reading_time_minutes);

        return $reading_time_minutes;

    }

    public static function getInformation()
    {
        $host = self::resolveHost();
        if ($host) {
            $match = self::matchSiteByHost($host);
            if ($match) {
                return $match;
            }
        }

        return self::findSiteByName(self::siteNameFromDatabase());
    }

    public static function siteName(): string
    {
        $site = self::getInformation();
        if (!empty($site['site_name'])) {
            return $site['site_name'];
        }

        if ($fromDb = self::siteNameFromDatabase()) {
            return $fromDb;
        }

        return self::normalizeHost((string) config('setting.template_url', env('TEMPLATE_URL', 'localhost')));
    }

    public static function templateUrl(): string
    {
        $site = self::getInformation();
        if (!empty($site['template_url'])) {
            return $site['template_url'];
        }

        $siteName = self::siteName();
        $match = self::sites()->first(fn ($site) => ($site['site_name'] ?? '') === $siteName);
        if (!empty($match['template_url'])) {
            return $match['template_url'];
        }

        return self::normalizeHost(str_replace(
            ['https://', 'http://'],
            '',
            (string) config('setting.template_url', env('TEMPLATE_URL', $siteName))
        ));
    }

    private static function resolveHost(): ?string
    {
        if (app()->bound('request') && request()) {
            $url = request()->header('site-name') ?? request()->getHost();
            if ($url) {
                return self::normalizeHost($url);
            }
        }

        return null;
    }

    private static function matchSiteByHost(string $host): ?array
    {
        return self::sites()->first(function ($site) use ($host) {
            foreach (['template_url', 'core_url'] as $key) {
                $candidate = self::normalizeHost($site[$key] ?? '');
                if ($candidate === '') {
                    continue;
                }

                if ($candidate === $host || str_contains($host, $candidate) || str_contains($candidate, $host)) {
                    return true;
                }
            }

            return false;
        });
    }

    private static function siteNameFromDatabase(): ?string
    {
        $database = config('database.connections.mysql.database', env('DB_DATABASE', ''));
        if (!is_string($database) || $database === '') {
            return null;
        }

        if (str_starts_with($database, 'cms_')) {
            return substr($database, 4);
        }

        return $database;
    }

    private static function findSiteByName(?string $siteName): ?array
    {
        if (!$siteName) {
            return null;
        }

        $normalized = self::normalizeHost($siteName);

        return self::sites()->first(function ($site) use ($normalized) {
            if (self::normalizeHost($site['site_name'] ?? '') === $normalized) {
                return true;
            }

            foreach (['template_url', 'core_url'] as $key) {
                if (self::normalizeHost($site[$key] ?? '') === $normalized) {
                    return true;
                }
            }

            return false;
        });
    }

    private static function sites()
    {
        static $sites;

        if ($sites === null) {
            $jsonData = Storage::disk('local')->get('private/config-sites-data.json');
            $sites = collect(json_decode($jsonData, true) ?: []);
        }

        return $sites;
    }

    private static function normalizeHost(string $url): string
    {
        $host = trim(strtolower(str_replace('www.', '', $url)));

        return preg_replace('/:\d+$/', '', $host) ?? $host;
    }

    public static function setSiteInformation(bool $abortIfMissing = true): bool
    {
        $site = self::getInformation();

        if (env('APP_ENV') == "production") {
            if (!$site) {
                if ($abortIfMissing) {
                    abort(404);
                }

                return false;
            }
            Config::set('setting.base_upload_folder', "sites/" . $site['site_name']);
            Config::set('setting.base_serve_folder', "");
            Config::set('setting.template_url', "https://" . $site['template_url']);
        }

        return true;
    }

    public static function checkRedirect($address)
    {
        $address = trim(str_replace(url('/'), "", $address), '/');
        $redirect = RedirectService::findNotRedirected($address);
        return $redirect;
    }
}

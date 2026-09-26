<?php

namespace App\Modules\Setting\Helper;

class GtmSnippetValidator
{
    /**
     * @return string|null Error message in Persian, or null if valid.
     */
    public static function validate(string $head_codes, string $body_codes): ?string
    {
        if (! self::hasHeadSnippet($head_codes)) {
            return 'برای فعال‌سازی ترکینگ، اسنیپت Google Tag Manager را در فیلد «تگ های سئو در head» قرار دهید.';
        }

        if (! self::hasBodySnippet($body_codes)) {
            return 'برای فعال‌سازی ترکینگ، اسنیپت Google Tag Manager (noscript) را در فیلد «تگ های سئو در body» قرار دهید.';
        }

        $head_id = self::extractContainerId($head_codes);
        $body_id = self::extractContainerId($body_codes);

        if ($head_id === null || $body_id === null) {
            return 'شناسه GTM (GTM-XXXX) در اسنیپت‌های head یا body یافت نشد.';
        }

        if (strcasecmp($head_id, $body_id) !== 0) {
            return 'شناسه GTM در head و body باید یکسان باشد.';
        }

        return null;
    }

    public static function hasHeadSnippet(string $html): bool
    {
        if (! preg_match('/googletagmanager\.com\/gtm\.js/i', $html)) {
            return false;
        }

        if (! preg_match('/GTM-[A-Z0-9]+/i', $html)) {
            return false;
        }

        return str_contains($html, 'gtm.start') || str_contains($html, 'dataLayer');
    }

    public static function hasBodySnippet(string $html): bool
    {
        if (! preg_match('/googletagmanager\.com\/ns\.html\?id=GTM-[A-Z0-9]+/i', $html)) {
            return false;
        }

        return stripos($html, '<noscript') !== false;
    }

    public static function extractContainerId(string $html): ?string
    {
        if (preg_match('/GTM-[A-Z0-9]+/i', $html, $matches)) {
            return strtoupper($matches[0]);
        }

        return null;
    }
}

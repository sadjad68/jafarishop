<?php

namespace App\Services\Legacy;

class LegacyMapper
{
    public const PRODUCT = 'App\\Modules\\Product\\Entities\\Product';
    public const BLOG = 'App\\Modules\\Blog\\Entities\\Blog';
    public const PRODUCT_CATEGORY = 'App\\Modules\\Product\\Entities\\ProductCategory';

    public static function isBasketOrder($statusId): bool
    {
        return (int) $statusId === 1;
    }

    public static function orderStatus($statusId): string
    {
        return match ((int) $statusId) {
            2 => 'paying',
            3, 4 => 'paid',
            5 => 'unpaid',
            default => 'unpaid',
        };
    }

    public static function shippingStatusId($statusId): ?int
    {
        return (int) $statusId === 4 ? 5 : null;
    }

    public static function discountType($type): string
    {
        $value = strtolower(trim((string) $type));

        return match ($value) {
            '2', 'cash', 'amount', 'fixed' => 'cash',
            default => 'percent',
        };
    }

    public static function seoMorph(?string $legacyType): ?string
    {
        if ($legacyType === null || trim($legacyType) === '') {
            return null;
        }

        $normalized = ltrim(str_replace('\\\\', '\\', $legacyType), '\\');

        return match ($normalized) {
            'App\\Models\\Product' => self::PRODUCT,
            'App\\Models\\Post' => self::BLOG,
            default => null,
        };
    }

    /**
     * @param  array<int, array{id:int|string, price:mixed, deleted_at?:mixed}>  $rows
     */
    public static function latestPrice(array $rows, $fallback): ?string
    {
        $alive = [];
        foreach ($rows as $row) {
            $deleted = $row['deleted_at'] ?? null;
            if ($deleted !== null && $deleted !== '') {
                continue;
            }
            $alive[] = $row;
        }

        usort($alive, function (array $left, array $right) {
            return ((int) $right['id']) <=> ((int) $left['id']);
        });

        foreach ($alive as $row) {
            $price = self::positivePrice($row['price'] ?? null);
            if ($price !== null) {
                return $price;
            }
        }

        return self::positivePrice($fallback);
    }

    public static function positivePrice($value): ?string
    {
        $decimal = self::decimalString($value);
        if ($decimal === null || (float) $decimal <= 0) {
            return null;
        }

        return $decimal;
    }

    public static function decimalString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $clean = preg_replace('/[^\d.\-]/', '', (string) $value);
        if ($clean === null || $clean === '' || !is_numeric($clean)) {
            return null;
        }

        $number = (float) $clean;
        if (floor($number) == $number) {
            return (string) (int) $number;
        }

        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }

    public static function stockForPrice(?string $price): int
    {
        return ($price !== null && (float) $price > 0) ? 1 : 0;
    }

    public static function activeForProduct($deletedAt): int
    {
        return ($deletedAt !== null && $deletedAt !== '') ? 0 : 1;
    }

    public static function fullName(?string $name, ?string $family): ?string
    {
        return self::combineText($name, $family);
    }

    public static function combineText(?string ...$parts): ?string
    {
        $kept = [];
        foreach ($parts as $part) {
            if ($part === null) {
                continue;
            }
            $part = trim($part);
            if ($part !== '') {
                $kept[] = $part;
            }
        }

        return $kept === [] ? null : implode("\n", $kept);
    }

    public static function positiveInt($value): ?int
    {
        if ($value === null || $value === '' || (int) $value <= 0) {
            return null;
        }

        return (int) $value;
    }

    public static function timestamp($value): string
    {
        $nullable = self::nullableTimestamp($value);

        return $nullable ?? date('Y-m-d H:i:s');
    }

    public static function nullableTimestamp($value): ?string
    {
        if ($value === null || $value === '' || $value === '0000-00-00 00:00:00') {
            return null;
        }

        return (string) $value;
    }

    public static function normalizeRedirect(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = trim($path);
        if ($path === '') {
            return null;
        }

        $path = preg_replace('#^https?://[^/]+#i', '', $path) ?? $path;
        $path = trim($path, "/ \t\n\r\0\x0B");

        return $path === '' ? null : $path;
    }

    /**
     * Build a public slug for brands (and similar entities) when legacy url is empty.
     * Matches BrandDTO / UrlRule conventions: spaces → dashes, strip disallowed chars.
     */
    public static function entitySlug(?string $url, ?string $title, int $id, string $prefix = 'item'): string
    {
        $normalized = self::normalizeRedirect($url);
        if ($normalized !== null) {
            return $normalized;
        }

        $slug = trim(str_replace([' ', '_'], '-', (string) $title));
        $slug = preg_replace('/[\'^£$%&*()}{@#~?><>,|=:.\/\\\\]+/u', '', $slug) ?? '';
        $slug = preg_replace('/-+/', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : $prefix . '-' . $id;
    }

    public static function normalizePlaceName(?string $name): string
    {
        $name = str_replace(['ي', 'ك', 'ة', "\u{200c}"], ['ی', 'ک', 'ه', ''], trim((string) $name));

        return mb_strtolower($name);
    }

    public static function faqQuestion(?string $title, ?string $question): ?string
    {
        $question = trim((string) $question);
        if ($question !== '') {
            return $question;
        }

        $title = trim((string) $title);

        return $title === '' ? null : $title;
    }

    public static function mediaFilename(int $mediaId): string
    {
        return 'legacy-' . $mediaId . '.webp';
    }

    /**
     * شماره‌های چسبیده در یک فیلد متنی قدیمی، مثل «۳۳۹۴۳۵۰۹ - ۰۲۱ - ۰۹۱۲۱۲۳۸۰۵۴».
     *
     * @return array<int, string>
     */
    public static function phoneNumbers(?string $raw): array
    {
        $latin = str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            (string) $raw
        );
        preg_match_all('/\d+/', $latin, $matches);
        $parts = $matches[0] ?? [];
        $numbers = [];
        $count = count($parts);
        for ($i = 0; $i < $count; $i++) {
            $current = $parts[$i];
            $next = $parts[$i + 1] ?? null;
            if ($next !== null && strlen($current) >= 7 && strlen($current) <= 8 && preg_match('/^0\d{2,3}$/', $next)) {
                $numbers[] = $next . $current;
                $i++;
                continue;
            }
            if ($next !== null && preg_match('/^0\d{2,3}$/', $current) && strlen($next) >= 7 && strlen($next) <= 8) {
                $numbers[] = $current . $next;
                $i++;
                continue;
            }
            if (strlen($current) >= 8) {
                $numbers[] = $current;
            }
        }

        return array_values(array_unique($numbers));
    }

    public static function siteNameFromSeoTitle(?string $title): ?string
    {
        $title = trim((string) $title);
        if ($title === '' || !str_contains($title, '|')) {
            return null;
        }
        $name = trim((string) substr($title, (int) strrpos($title, '|') + 1));

        return $name === '' ? null : $name;
    }

    public static function siteLink(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '' || str_starts_with($url, '/tmp/')) {
            return null;
        }
        if (preg_match('#^https?://(?:www\.)?topickala\.ir(?P<path>/.*)?$#i', $url, $matches)) {
            $path = $matches['path'] ?? '/';

            return $path === '' ? '/' : $path;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return '/' . ltrim($url, '/');
    }

    public static function whatsappNumber(?string $link): ?string
    {
        if (!preg_match('/(\d{10,15})/', (string) $link, $matches)) {
            return null;
        }
        $digits = $matches[1];
        if (str_starts_with($digits, '98') && strlen($digits) > 10) {
            $digits = substr($digits, 2);
        }
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return $digits === '' ? null : $digits;
    }

    public static function serviceUrl(int $id, ?string $title = null): string
    {
        return 'service-' . $id;
    }

    public static function serviceOrderStatusLabel($status): string
    {
        return match ((int) $status) {
            2 => 'انجام شده',
            1 => 'ثبت شده',
            default => 'نامشخص',
        };
    }

    public static function serviceRequestIsRead($status): bool
    {
        return (int) $status === 2;
    }

    public static function serviceRequestDescription(
        ?string $serviceTitle,
        $status,
        ?string $transId,
        ?string $refId,
        ?string $doneWork
    ): string {
        $lines = [
            'خدمت: ' . (self::combineText($serviceTitle) ?? 'نامشخص'),
            'وضعیت: ' . self::serviceOrderStatusLabel($status),
        ];
        $transId = trim((string) $transId);
        if ($transId !== '') {
            $lines[] = 'کد تراکنش: ' . $transId;
        }
        $refId = trim((string) $refId);
        if ($refId !== '') {
            $lines[] = 'کد پیگیری: ' . $refId;
        }
        $doneWork = trim((string) $doneWork);
        if ($doneWork !== '') {
            $lines[] = 'کار انجام‌شده: ' . $doneWork;
        }

        return implode("\n", $lines);
    }

    /**
     * @return array<int, array{old:string, new:string}>
     */
    public static function categoryRedirects(int $id, ?string $url): array
    {
        $url = self::normalizeRedirect($url);
        if ($url === null || $id <= 0) {
            return [];
        }

        return [
            ['old' => 'categories/' . $id, 'new' => $url],
        ];
    }

    /**
     * @return array<int, array{old:string, new:string}>
     */
    public static function productRedirects(int $id, ?string $productUrl, ?string $categoryUrl): array
    {
        $productUrl = self::normalizeRedirect($productUrl);
        $categoryUrl = self::normalizeRedirect($categoryUrl);
        if ($productUrl === null || $id <= 0) {
            return [];
        }

        if ($categoryUrl !== null) {
            return [
                ['old' => 'products/' . $id, 'new' => $categoryUrl . '/' . $productUrl],
            ];
        }

        return [
            ['old' => 'products/' . $id, 'new' => 'product/' . $productUrl],
        ];
    }

    /**
     * @return array<int, array{old:string, new:string}>
     */
    public static function postRedirects(int $id, ?string $postUrl, ?string $categoryUrl): array
    {
        $postUrl = self::normalizeRedirect($postUrl);
        $categoryUrl = self::normalizeRedirect($categoryUrl);
        if ($postUrl === null || $id <= 0) {
            return [];
        }

        if ($categoryUrl === null) {
            return [];
        }

        return [
            ['old' => 'post/' . $postUrl, 'new' => 'article/' . $categoryUrl . '/' . $postUrl],
        ];
    }

    /**
     * @return array<int, array{old:string, new:string}>
     */
    public static function brandRedirects(int $id, ?string $url): array
    {
        $url = self::normalizeRedirect($url);
        if ($url === null || $id <= 0) {
            return [];
        }

        return [
            ['old' => 'brand/' . $url, 'new' => 'brands/' . $id],
        ];
    }
}

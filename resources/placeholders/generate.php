<?php

declare(strict_types=1);

/*
 * Generates the fallback artwork in public/assets/notfounds/.
 *
 * Every content type (product, category, brand, article, service, ...) gets its
 * own composition so an empty record is still readable at a glance instead of
 * showing one generic grey box everywhere.
 *
 * Rasterizing goes through Chrome because the compositions rely on SVG
 * gradients/filters that GD cannot draw. `sips` handles the final encode.
 *
 *   php resources/placeholders/generate.php            # everything
 *   php resources/placeholders/generate.php product-img.jpg brand.jpg
 */

const OUT_DIR = __DIR__ . '/../../public/assets/notfounds';
const TMP_DIR = __DIR__ . '/../../storage/tmp/placeholders';
const FONT_DIR = __DIR__ . '/../../public/assets/site/fonts/iranyekan/woff';

const CHROME = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';

/** Neutral ramp so the artwork survives every admin colour theme. */
const INK = [
    900 => '#14161a',
    700 => '#3a3d44',
    500 => '#71757d',
    400 => '#9aa1ac',
    300 => '#aeb2ba',
    200 => '#d6dae1',
    100 => '#eef0f3',
];

/**
 * Stroke icons drawn in a 24x24 box, rounded joins, no fills.
 */
function icons(): array
{
    return [
        'bag' => 'M5.2 8.4h13.6l-1.1 11.3a2.4 2.4 0 0 1-2.4 2.2H8.7a2.4 2.4 0 0 1-2.4-2.2L5.2 8.4Z
                  M9.1 8.4V6.2a2.9 2.9 0 0 1 5.8 0v2.2',
        'grid' => 'M4 4.8h6v6H4zM14 4.8h6v6h-6zM4 13.2h6v6H4zM14 13.2h6v6h-6z',
        'badge' => 'M12 3.4l2.5 1.6 3-.1 1 2.8 2.3 1.9-1.2 2.7.4 3-2.8 1.2-1.7 2.5-3-.5-2.7 1.4-2.1-2.1-3-.7.1-3-1.9-2.4 1.6-2.5-.2-3 2.9-.9Z
                    M8.9 12.1l2.1 2.1 4.1-4.3',
        'article' => 'M6 3.8h9.4L19 7.4v12.8H6zM15 3.8v3.9h4M9 11.5h7M9 14.9h7M9 18.2h4.5',
        'gear' => 'M12 8.6a3.4 3.4 0 1 0 0 6.8 3.4 3.4 0 0 0 0-6.8Z
                   M12 2.6v2.4M12 19v2.4M5.4 5.4l1.7 1.7M16.9 16.9l1.7 1.7M2.6 12H5M19 12h2.4M5.4 18.6l1.7-1.7M16.9 7.1l1.7-1.7',
        'package' => 'M20.8 7.9 12 3 3.2 7.9v8.2L12 21l8.8-4.9zM3.2 7.9 12 12.8l8.8-4.9M12 12.8V21M7.6 5.4l8.8 4.9',
        'layers' => 'M4.4 7.4h11.2v9.2H4.4zM8.4 4.6h11.2v9.2M7.2 13.4l2.6-2.9 2.3 2.5 1.6-1.6 1.9 2',
        'camera' => 'M3.6 8.4h2.9l1.5-2.4h8l1.5 2.4h2.9v10.2H3.6zM12 10.4a3.6 3.6 0 1 0 0 7.2 3.6 3.6 0 0 0 0-7.2Z',
        'rosette' => 'M12 2.9a5.7 5.7 0 1 0 0 11.4 5.7 5.7 0 0 0 0-11.4ZM8.2 13.4 6.6 21.1l5.4-2.6 5.4 2.6-1.6-7.7M9.6 8.6l1.7 1.7 3.1-3.2',
        'trophy' => 'M7.4 4.2h9.2v4.4a4.6 4.6 0 0 1-9.2 0zM7.4 5.4H4.9v1.5a3 3 0 0 0 2.5 3M16.6 5.4h2.5v1.5a3 3 0 0 1-2.5 3M12 13.2v3.6M8.4 20.4h7.2l-.7-3.6H9.1z',
        'user' => 'M12 4.4a3.9 3.9 0 1 0 0 7.8 3.9 3.9 0 0 0 0-7.8ZM4.8 20.4a7.2 7.2 0 0 1 14.4 0',
        'play' => 'M12 3.2a8.8 8.8 0 1 0 0 17.6 8.8 8.8 0 0 0 0-17.6ZM10.2 8.4l5.6 3.6-5.6 3.6z',
        'discount' => 'M20.3 12.9 13 20.2a1.9 1.9 0 0 1-2.7 0l-6.5-6.5V4.6h9.1l7.4 7.4a.9.9 0 0 1 0 .9Z
                       M7.6 7.9h.01M15.4 10.2l-4.6 4.6M10.9 10.5h.01M15.1 14.5h.01',
        'clock' => 'M12 3.2a8.8 8.8 0 1 0 0 17.6 8.8 8.8 0 0 0 0-17.6ZM12 7.4V12l3.4 2',
        'image' => 'M3.9 4.6h16.2v14.8H3.9zM8.4 10.1a1.7 1.7 0 1 0 0-3.4 1.7 1.7 0 0 0 0 3.4ZM3.9 16.3l4.8-5 3.3 3.3 3-3 5.1 5',
        'headset' => 'M4.4 15.1v-2.9a7.6 7.6 0 0 1 15.2 0v2.9M4.4 13.4h2.2v5.2H4.4a1.5 1.5 0 0 1-1.5-1.5v-2.2a1.5 1.5 0 0 1 1.5-1.5ZM19.6 13.4h-2.2v5.2h2.2a1.5 1.5 0 0 0 1.5-1.5v-2.2a1.5 1.5 0 0 0-1.5-1.5Z',
        'sparkle' => 'M12 3.4l1.9 5.1 5.1 1.9-5.1 1.9L12 17.4l-1.9-5.1L5 10.4l5.1-1.9zM18.4 16.1l.8 2.1 2.1.8-2.1.8-.8 2.1-.8-2.1-2.1-.8 2.1-.8z',
        'slides' => 'M6.6 6.4h10.8v11.2H6.6zM3.4 9.1v5.8M20.6 9.1v5.8M9.9 20.6h4.2',
        'mark' => 'M12 2.8 21 7.6v8.8L12 21.2 3 16.4V7.6zM12 12l9-4.4M12 12v9.2M12 12 3 7.6',
    ];
}

/**
 * name => [width, height, style, icon, label]
 *
 * styles: tile (light card), dark (light text over ink), hero (wide banner),
 *         bare (icon only), avatar (round), wordmark (text only)
 */
function specs(): array
{
    return [
        'product-img.jpg' => [1000, 1000, 'tile', 'bag', 'تصویر محصول'],
        'category-img.jpg' => [300, 300, 'bare', 'grid', null],
        'brand.jpg' => [300, 300, 'bare', 'badge', null],
        'blogs.jpg' => [450, 450, 'tile', 'article', 'مقاله'],
        'blogs-header.jpg' => [1920, 350, 'dark', 'article', 'وبلاگ'],
        'services-img.jpg' => [935, 500, 'tile', 'gear', 'خدمات'],
        'service-header-detail.jpg' => [1920, 900, 'dark', 'gear', 'خدمات'],
        'package-img.jpg' => [450, 205, 'tile', 'package', 'پکیج'],
        'samples-img.jpg' => [1000, 1000, 'tile', 'layers', 'نمونه کار'],
        'gallery-img.jpg' => [400, 581, 'tile', 'camera', 'گالری'],
        'certificate.jpg' => [500, 500, 'tile', 'rosette', 'گواهینامه'],
        'honors-img.jpg' => [300, 300, 'tile', 'trophy', 'افتخارات'],
        'team-img.jpg' => [250, 250, 'bare', 'user', null],
        'user.webp' => [100, 100, 'avatar', 'user', null],
        'default.jpg' => [369, 462, 'tile', 'image', 'تصویر'],
        'cover-video.jpg' => [510, 300, 'dark', 'play', 'ویدیو'],
        'discounted-back.jpg' => [1000, 667, 'dark', 'discount', 'پیشنهاد ویژه'],
        'time-back.jpg' => [2100, 1200, 'dark', 'clock', null],
        'time-sm-img.jpg' => [310, 450, 'tile', 'discount', 'پیشنهاد ویژه'],
        'slider-desktop-theme1.jpg' => [2000, 1000, 'hero', 'slides', 'بنر اسلایدر'],
        'slider-mobile-theme1.jpg' => [500, 750, 'hero', 'slides', 'بنر موبایل'],
        'slider-desktop-theme2.jpg' => [2850, 623, 'hero', 'slides', 'بنر اسلایدر'],
        'slider-mobile-theme2.jpg' => [1050, 675, 'hero', 'slides', 'بنر موبایل'],
        'support-img-top.jpg' => [72, 72, 'glyph', 'headset', null],
        'support-img-bottom.jpg' => [132, 130, 'glyph', 'headset', null],
        'logo.jpg' => [150, 85, 'wordmark', 'mark', 'لوگو'],
        'logo-footer.jpg' => [150, 85, 'wordmark', 'mark', 'لوگو'],
        'favicon.jpg' => [64, 64, 'bare', 'mark', null],
        'slogan.jpg' => [64, 64, 'glyph', 'sparkle', null],
        'Off-Banner.png' => [201, 202, 'sticker', 'discount', null],
        'Off-Banner2.png' => [196, 318, 'sticker', 'sparkle', null],
    ];
}

function fontFace(): string
{
    $faces = [
        ['iranyekanwebmediumfanum.woff', 500],
        ['iranyekanwebboldfanum.woff', 700],
    ];

    $css = '';
    foreach ($faces as [$file, $weight]) {
        $path = FONT_DIR . '/' . $file;
        if (! is_file($path)) {
            continue;
        }
        $data = base64_encode((string) file_get_contents($path));
        $css .= "@font-face{font-family:'PH';font-weight:{$weight};font-style:normal;"
            . "src:url(data:font/woff;base64,{$data}) format('woff');}";
    }

    return $css;
}

/**
 * Icon glyph scaled from the 24x24 authoring box to an arbitrary size.
 *
 * Stroke weight grows sub-linearly with the icon so large artwork keeps the
 * thin hairline look instead of turning into fat marker strokes.
 */
function icon(string $name, float $cx, float $cy, float $size, string $stroke, float $weight = 1.0): string
{
    $path = preg_replace('/\s+/', ' ', trim(icons()[$name]));
    $scale = $size / 24;
    $visual = max(1.15, min($size * 0.042 * $weight, 7.0));

    return sprintf(
        '<g transform="translate(%.2f %.2f) scale(%.4f)" fill="none" stroke="%s" stroke-width="%.3f" '
        . 'stroke-linecap="round" stroke-linejoin="round"><path d="%s"/></g>',
        $cx - $size / 2,
        $cy - $size / 2,
        $scale,
        $stroke,
        $visual / $scale,
        $path
    );
}

/** Rounded rectangle helper. */
function chip(float $x, float $y, float $w, float $h, float $r, string $fill, string $stroke = 'none', float $sw = 1): string
{
    return sprintf(
        '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" rx="%.2f" fill="%s" stroke="%s" stroke-width="%.2f"/>',
        $x,
        $y,
        $w,
        $h,
        $r,
        $fill,
        $stroke,
        $sw
    );
}

function text(string $value, float $x, float $y, float $size, string $fill, int $weight = 500, float $spacing = 0): string
{
    return sprintf(
        '<text x="%.2f" y="%.2f" font-family="PH, Tahoma, sans-serif" font-size="%.2f" font-weight="%d" '
        . 'letter-spacing="%.2f" fill="%s" text-anchor="middle" direction="rtl">%s</text>',
        $x,
        $y,
        $size,
        $weight,
        $spacing,
        $fill,
        htmlspecialchars($value, ENT_XML1)
    );
}

function svg(int $w, int $h, string $style, string $iconName, ?string $label): string
{
    $min = min($w, $h);
    $cx = $w / 2;
    $defs = '<style>' . fontFace() . '</style>';
    $body = '';

    // Icon plate + label sizing scales with the smaller edge. Most of these
    // assets are consumed far smaller than their source, so the plate takes a
    // generous share of the frame to stay readable once downscaled.
    $plate = max(28.0, min($min * 0.44, $min > 700 ? 280.0 : 190.0));
    $iconSize = $plate * 0.54;
    $labelSize = max(9.0, min($min * 0.068, 32.0));
    $hasLabel = $label !== null && $min >= 150;
    $blockH = $plate + ($hasLabel ? $labelSize * 2.5 : 0);
    $plateY = $h / 2 - $blockH / 2 + $plate / 2;
    $labelY = $plateY + $plate / 2 + $labelSize * 1.7;

    switch ($style) {
        case 'dark':
        case 'wordmark-dark':
            $defs .= '<linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">'
                . '<stop offset="0" stop-color="#1d2129"/><stop offset=".55" stop-color="#141720"/>'
                . '<stop offset="1" stop-color="#0c0e13"/></linearGradient>'
                . '<radialGradient id="glow" cx=".22" cy=".18" r=".75">'
                . '<stop offset="0" stop-color="#ffffff" stop-opacity=".10"/>'
                . '<stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient>'
                . '<radialGradient id="glow2" cx=".85" cy=".9" r=".6">'
                . '<stop offset="0" stop-color="#ffffff" stop-opacity=".06"/>'
                . '<stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient>'
                . '<pattern id="tex" width="26" height="26" patternUnits="userSpaceOnUse" patternTransform="rotate(35)">'
                . '<line x1="0" y1="0" x2="0" y2="26" stroke="#ffffff" stroke-opacity=".045" stroke-width="1"/></pattern>';

            $body .= sprintf('<rect width="%d" height="%d" fill="url(#bg)"/>', $w, $h);
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#tex)"/>', $w, $h);
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#glow)"/>', $w, $h);
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#glow2)"/>', $w, $h);

            if ($style === 'wordmark-dark') {
                $body .= icon($iconName, $w / 2 - $w * 0.19, $h / 2, $h * 0.42, 'rgba(255,255,255,.5)', 1.05);
                $body .= text((string) $label, $w / 2 + $w * 0.08, $h / 2 + $h * 0.11, $h * 0.3, 'rgba(255,255,255,.66)', 700, 1);
                break;
            }

            $body .= chip($cx - $plate / 2, $plateY - $plate / 2, $plate, $plate, $plate * 0.3, 'rgba(255,255,255,.055)', 'rgba(255,255,255,.13)', max(1.0, $min / 400));
            $body .= icon($iconName, $cx, $plateY, $iconSize, 'rgba(255,255,255,.6)');
            if ($hasLabel) {
                $body .= text((string) $label, $cx, $labelY, $labelSize, 'rgba(255,255,255,.6)', 500, 0.5);
                $body .= chip($cx - $labelSize * 1.1, $labelY + $labelSize * 0.75, $labelSize * 2.2, max(2.0, $labelSize * 0.1), $labelSize, 'rgba(255,255,255,.22)');
            }
            break;

        case 'hero':
            $defs .= '<linearGradient id="bg" x1="0" y1="0" x2=".9" y2="1">'
                . '<stop offset="0" stop-color="#fdfefe"/><stop offset=".5" stop-color="#f1f4f8"/>'
                . '<stop offset="1" stop-color="#e4e9f0"/></linearGradient>'
                . '<radialGradient id="b1" cx=".5" cy=".5" r=".5">'
                . '<stop offset="0" stop-color="#ffffff" stop-opacity=".95"/>'
                . '<stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient>'
                . '<radialGradient id="b2" cx=".5" cy=".5" r=".5">'
                . '<stop offset="0" stop-color="#14161a" stop-opacity=".05"/>'
                . '<stop offset="1" stop-color="#14161a" stop-opacity="0"/></radialGradient>'
                . '<filter id="sh" x="-40%" y="-40%" width="180%" height="180%">'
                . '<feDropShadow dx="0" dy="' . max(3, $min * 0.02) . '" stdDeviation="' . max(4, $min * 0.03)
                . '" flood-color="#14161a" flood-opacity=".12"/></filter>';

            $body .= sprintf('<rect width="%d" height="%d" fill="url(#bg)"/>', $w, $h);
            $body .= sprintf('<ellipse cx="%.0f" cy="%.0f" rx="%.0f" ry="%.0f" fill="url(#b1)"/>', $w * 0.72, $h * 0.1, $w * 0.42, $h * 0.7);
            $body .= sprintf('<ellipse cx="%.0f" cy="%.0f" rx="%.0f" ry="%.0f" fill="url(#b2)"/>', $w * 0.12, $h * 0.95, $w * 0.36, $h * 0.6);

            // Skeleton of a real banner: media plate on one side, copy stack on the other.
            $portrait = $h > $w * 0.9;
            if ($portrait) {
                $pw = $w * 0.52;
                $ph = $pw;
                $px = $cx - $pw / 2;
                $py = $h * 0.16;
                $body .= chip($px, $py, $pw, $ph, $pw * 0.16, '#ffffff', 'rgba(20,22,26,.06)', 1.2) . '';
                $body .= icon($iconName, $cx, $py + $ph / 2, $ph * 0.4, INK[400]);
                $barW = $w * 0.62;
                $body .= chip($cx - $barW / 2, $py + $ph + $h * 0.06, $barW, $h * 0.028, $h * 0.02, INK[200]);
                $body .= chip($cx - $barW * 0.34, $py + $ph + $h * 0.115, $barW * 0.68, $h * 0.022, $h * 0.02, '#e2e6ec');
                $body .= text((string) $label, $cx, $py + $ph + $h * 0.23, max(12.0, $w * 0.055), INK[500], 500, 0.5);
                $pill = $w * 0.36;
                $body .= chip($cx - $pill / 2, $py + $ph + $h * 0.27, $pill, $h * 0.062, $h * 0.031, 'rgba(20,22,26,.055)', 'rgba(20,22,26,.08)', 1);
                break;
            }

            // Media plate + copy stack are laid out as one centred group so very
            // wide banners (4:1 and beyond) do not end up with a hollow middle.
            $plateH = min($h * 0.56, $w * 0.22);
            $plateW = $plateH;
            $gap = $plateH * 0.42;
            $copyW = min($plateH * 2.3, $w * 0.42);
            $groupW = $plateW + $gap + $copyW;
            $groupX = $cx - $groupW / 2;
            $mid = $h * 0.47;

            $plateX = $groupX;
            $plateY2 = $mid - $plateH / 2;
            $body .= '<g filter="url(#sh)">' . chip($plateX, $plateY2, $plateW, $plateH, $plateH * 0.16, '#ffffff') . '</g>';
            $body .= chip($plateX, $plateY2, $plateW, $plateH, $plateH * 0.16, 'none', 'rgba(20,22,26,.06)', max(1.0, $h * 0.002));
            $body .= icon($iconName, $plateX + $plateW / 2, $mid, $plateH * 0.4, INK[400]);

            // Copy column reads right-to-left, matching the RTL front-end.
            $copyRight = $groupX + $groupW;
            $barH = max(6.0, $plateH * 0.13);
            $top = $mid - $barH * 2.6;
            $body .= chip($copyRight - $copyW, $top, $copyW, $barH, $barH / 2, INK[200]);
            $body .= chip($copyRight - $copyW * 0.72, $top + $barH * 1.75, $copyW * 0.72, $barH * 0.62, $barH / 2, '#e3e7ed');
            $body .= chip($copyRight - $copyW * 0.86, $top + $barH * 2.95, $copyW * 0.86, $barH * 0.62, $barH / 2, '#e8ebf0');

            $pillH = $barH * 1.9;
            $pillW = max($pillH * 2.6, $copyW * 0.42);
            $pillY = $top + $barH * 4.35;
            $body .= chip($copyRight - $pillW, $pillY, $pillW, $pillH, $pillH / 2, 'rgba(20,22,26,.05)', 'rgba(20,22,26,.09)', max(1.0, $h * 0.0018));
            $body .= text((string) $label, $copyRight - $pillW / 2, $pillY + $pillH * 0.66, $pillH * 0.4, INK[500], 500, 0.5);

            // Carousel dots hint the slider context.
            $dot = max(4.0, $h * 0.013);
            for ($i = 0; $i < 3; $i++) {
                $active = $i === 0;
                $body .= sprintf(
                    '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" rx="%.2f" fill="%s"/>',
                    $cx - $dot * 4 + $i * $dot * 3.4,
                    $h - max($h * 0.075, $dot * 4),
                    $active ? $dot * 3.2 : $dot * 1.6,
                    $dot * 1.6,
                    $dot,
                    $active ? INK[400] : INK[200]
                );
            }
            break;

        case 'sticker':
            $defs .= '<linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">'
                . '<stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#eef1f6"/></linearGradient>'
                . '<filter id="sh" x="-40%" y="-40%" width="180%" height="180%">'
                . '<feDropShadow dx="0" dy="6" stdDeviation="9" flood-color="#14161a" flood-opacity=".16"/></filter>';
            $pad = $min * 0.08;
            $bw = $w - $pad * 2;
            $bh = $h - $pad * 2;
            $body .= '<g filter="url(#sh)">' . chip($pad, $pad, $bw, $bh, $min * 0.22, 'url(#bg)') . '</g>';
            $body .= chip($pad, $pad, $bw, $bh, $min * 0.22, 'none', 'rgba(20,22,26,.08)', 1.4);
            $body .= icon($iconName, $w / 2, $h / 2, $min * 0.42, INK[400], 1.15);
            break;

        case 'avatar':
            $defs .= '<radialGradient id="bg" cx=".35" cy=".28" r=".85">'
                . '<stop offset="0" stop-color="#f8fafc"/><stop offset="1" stop-color="#e6eaf1"/></radialGradient>';
            $r = $min / 2;
            $body .= sprintf('<circle cx="%.2f" cy="%.2f" r="%.2f" fill="url(#bg)"/>', $w / 2, $h / 2, $r);
            $body .= sprintf(
                '<circle cx="%.2f" cy="%.2f" r="%.2f" fill="none" stroke="rgba(20,22,26,.07)" stroke-width="%.2f"/>',
                $w / 2,
                $h / 2,
                $r - 1,
                max(1.0, $min * 0.02)
            );
            $body .= icon($iconName, $w / 2, $h / 2, $min * 0.5, INK[400], 1.15);
            break;

        case 'glyph':
            // Pure white field with no frame: these sit on tinted cards and are
            // blended with multiply, which makes white disappear entirely.
            $body .= sprintf('<rect width="%d" height="%d" fill="#ffffff"/>', $w, $h);
            $body .= icon($iconName, $w / 2, $h / 2, $min * 0.72, INK[400], 1.15);
            break;

        case 'bare':
            // Full bleed: these end up as JPEGs, where rounded corners would be
            // flattened into hard white wedges.
            $defs .= '<linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">'
                . '<stop offset="0" stop-color="#f9fafc"/><stop offset="1" stop-color="#e7ebf2"/></linearGradient>';
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#bg)"/>', $w, $h);
            $body .= icon($iconName, $w / 2, $h / 2, $min * 0.56, INK[400], 1.15);
            $body .= sprintf(
                '<rect x=".5" y=".5" width="%.1f" height="%.1f" fill="none" stroke="rgba(20,22,26,.06)" stroke-width="1"/>',
                $w - 1,
                $h - 1
            );
            break;

        case 'wordmark':
            $defs .= '<linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">'
                . '<stop offset="0" stop-color="#fbfcfe"/><stop offset="1" stop-color="#eceff4"/></linearGradient>';
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#bg)"/>', $w, $h);
            $body .= icon($iconName, $w / 2 - $w * 0.19, $h / 2, $h * 0.42, INK[400], 1.05);
            $body .= text((string) $label, $w / 2 + $w * 0.08, $h / 2 + $h * 0.11, $h * 0.3, INK[500], 700, 1);
            break;

        default: // tile
            $defs .= '<linearGradient id="bg" x1="0" y1="0" x2=".85" y2="1">'
                . '<stop offset="0" stop-color="#fcfdfe"/><stop offset=".55" stop-color="#f2f4f8"/>'
                . '<stop offset="1" stop-color="#e8ecf2"/></linearGradient>'
                . '<radialGradient id="glow" cx=".3" cy=".18" r=".8">'
                . '<stop offset="0" stop-color="#ffffff" stop-opacity=".9"/>'
                . '<stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient>'
                . '<pattern id="dots" width="' . max(14, $min * 0.06) . '" height="' . max(14, $min * 0.06) . '" patternUnits="userSpaceOnUse">'
                . '<circle cx="1.5" cy="1.5" r="' . max(0.8, $min * 0.0035) . '" fill="#14161a" fill-opacity=".05"/></pattern>'
                . '<filter id="sh" x="-50%" y="-50%" width="200%" height="200%">'
                . '<feDropShadow dx="0" dy="' . max(2, $plate * 0.06) . '" stdDeviation="' . max(3, $plate * 0.09)
                . '" flood-color="#14161a" flood-opacity=".1"/></filter>';

            $body .= sprintf('<rect width="%d" height="%d" fill="url(#bg)"/>', $w, $h);
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#dots)"/>', $w, $h);
            $body .= sprintf('<rect width="%d" height="%d" fill="url(#glow)"/>', $w, $h);

            $body .= '<g filter="url(#sh)">'
                . chip($cx - $plate / 2, $plateY - $plate / 2, $plate, $plate, $plate * 0.28, '#ffffff')
                . '</g>';
            $body .= chip($cx - $plate / 2, $plateY - $plate / 2, $plate, $plate, $plate * 0.28, 'none', 'rgba(20,22,26,.06)', max(1.0, $plate * 0.008));
            $body .= icon($iconName, $cx, $plateY, $iconSize, INK[400]);

            if ($hasLabel) {
                $body .= text((string) $label, $cx, $labelY, $labelSize, INK[500], 500, 0.5);
                $body .= chip($cx - $labelSize * 1.1, $labelY + $labelSize * 0.8, $labelSize * 2.2, max(2.0, $labelSize * 0.1), $labelSize, INK[200]);
            }

            // Hairline frame keeps the tile from bleeding into white cards.
            $body .= sprintf(
                '<rect x=".5" y=".5" width="%.1f" height="%.1f" fill="none" stroke="rgba(20,22,26,.055)" stroke-width="1"/>',
                $w - 1,
                $h - 1
            );
            break;
    }

    return '<?xml version="1.0" encoding="UTF-8"?>'
        . sprintf('<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d">', $w, $h, $w, $h)
        . '<defs>' . $defs . '</defs>' . $body . '</svg>';
}

function run(array $cmd): array
{
    $escaped = implode(' ', array_map('escapeshellarg', $cmd)) . ' 2>&1';
    exec($escaped, $out, $code);

    return [$code, implode("\n", $out)];
}

// ---------------------------------------------------------------------------

if (! is_file(CHROME)) {
    fwrite(STDERR, "Chrome not found at " . CHROME . PHP_EOL);
    exit(1);
}

@mkdir(TMP_DIR, 0775, true);

$only = array_slice($argv, 1);
$specs = specs();
if ($only) {
    $specs = array_intersect_key($specs, array_flip($only));
}

$failed = [];
foreach ($specs as $name => [$w, $h, $style, $iconName, $label]) {
    $base = pathinfo($name, PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $svgPath = TMP_DIR . "/{$base}.svg";
    $pngPath = TMP_DIR . "/{$base}.png";
    $target = OUT_DIR . '/' . $name;

    file_put_contents($svgPath, svg($w, $h, $style, $iconName, $label));
    @unlink($pngPath);

    [$code, $out] = run([
        CHROME,
        '--headless=new',
        '--disable-gpu',
        '--no-sandbox',
        '--hide-scrollbars',
        '--force-device-scale-factor=1',
        '--default-background-color=00000000',
        '--window-size=' . $w . ',' . $h,
        '--screenshot=' . $pngPath,
        'file://' . $svgPath,
    ]);

    if (! is_file($pngPath)) {
        $failed[$name] = "chrome failed ({$code}): {$out}";
        continue;
    }

    // A short viewport would silently crop the art, so fail loudly instead.
    $rendered = getimagesize($pngPath);
    if ($rendered === false || $rendered[0] !== $w || $rendered[1] !== $h) {
        $failed[$name] = sprintf('expected %dx%d, chrome produced %s', $w, $h, $rendered ? "{$rendered[0]}x{$rendered[1]}" : 'nothing');
        continue;
    }

    if ($ext === 'png') {
        copy($pngPath, $target);
    } elseif ($ext === 'webp') {
        // sips cannot encode webp, so GD handles this one.
        $im = imagecreatefrompng($pngPath);
        if ($im === false) {
            $failed[$name] = 'gd could not read the render';
            continue;
        }
        imagealphablending($im, false);
        imagesavealpha($im, true);
        if (! imagewebp($im, $target, 88)) {
            $failed[$name] = 'gd could not write webp';
            imagedestroy($im);
            continue;
        }
        imagedestroy($im);
    } else {
        [$c2, $o2] = run(['sips', '-s', 'format', 'jpeg', '-s', 'formatOptions', '86', $pngPath, '--out', $target]);
        if ($c2 !== 0) {
            $failed[$name] = "sips failed: {$o2}";
            continue;
        }
    }

    unlink($pngPath);
    printf("%-32s %sx%s %s\n", $name, $w, $h, $style);
}

if ($failed) {
    fwrite(STDERR, PHP_EOL . "FAILED:" . PHP_EOL);
    foreach ($failed as $name => $why) {
        fwrite(STDERR, "  {$name}: {$why}" . PHP_EOL);
    }
    exit(1);
}

echo PHP_EOL . 'done: ' . count($specs) . ' file(s)' . PHP_EOL;

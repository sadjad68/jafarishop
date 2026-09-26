<?php

namespace App\Modules\General\Helper;

class ColorHelper
{
    public static function parseToRgb(string $color): array
    {
        $color = trim($color);

        if (preg_match('/^#([0-9a-f]{3})$/i', $color, $matches)) {
            return [
                hexdec($matches[1][0] . $matches[1][0]),
                hexdec($matches[1][1] . $matches[1][1]),
                hexdec($matches[1][2] . $matches[1][2]),
            ];
        }

        if (preg_match('/^#([0-9a-f]{6})$/i', $color, $matches)) {
            return [
                hexdec(substr($matches[1], 0, 2)),
                hexdec(substr($matches[1], 2, 2)),
                hexdec(substr($matches[1], 4, 2)),
            ];
        }

        if (preg_match('/rgba?\(\s*([\d.]+)[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $color, $matches)) {
            return [(int) $matches[1], (int) $matches[2], (int) $matches[3]];
        }

        return [0, 0, 0];
    }

    public static function relativeLuminance(string $color): float
    {
        [$red, $green, $blue] = self::parseToRgb($color);

        $channels = array_map(function (int $channel): float {
            $normalized = $channel / 255;

            return $normalized <= 0.03928
                ? $normalized / 12.92
                : pow(($normalized + 0.055) / 1.055, 2.4);
        }, [$red, $green, $blue]);

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }

    public static function contrastTextForGradient(array $colors): string
    {
        $luminances = array_map([self::class, 'relativeLuminance'], $colors);
        $lightCount = count(array_filter($luminances, fn (float $luminance) => $luminance > 0.5));
        $darkCount = count($luminances) - $lightCount;

        if ($lightCount > $darkCount) {
            return '#000';
        }

        if ($darkCount > $lightCount) {
            return '#fff';
        }

        $average = array_sum($luminances) / count($luminances);

        return $average > 0.5 ? '#000' : '#fff';
    }

    public static function embossedTextShadow(string $textColor): string
    {
        if ($textColor === '#fff') {
            return '0 1px 2px rgba(0,0,0,0.75), 0 0 4px rgba(0,0,0,0.5), 1px 1px 1px rgba(0,0,0,0.35)';
        }

        return '0 1px 0 rgba(255,255,255,0.9), 0 0 3px rgba(255,255,255,0.65), -1px -1px 1px rgba(255,255,255,0.45)';
    }

    public static function themeGradient(array $colorData): string
    {
        return sprintf(
            'linear-gradient(90deg, %s 0%%, %s 50%%, %s 100%%)',
            $colorData['color-body'],
            $colorData['color-two'],
            $colorData['color-one']
        );
    }

    public static function themeTextStyle(array $colorData): string
    {
        $textColor = self::contrastTextForGradient([
            $colorData['color-body'],
            $colorData['color-two'],
            $colorData['color-one'],
        ]);

        $textShadow = self::embossedTextShadow($textColor);

        return "color: {$textColor}; text-shadow: {$textShadow}; font-weight: 600;";
    }

    public static function themeOptionStyle(array $colorData): string
    {
        return 'background: ' . self::themeGradient($colorData) . '; ' . self::themeTextStyle($colorData);
    }

    public static function themeSwatchVars(array $colorData): string
    {
        $colorOne = $colorData['color-one'] ?? '#888';
        $colorTwo = $colorData['color-two'] ?? '#bbb';
        $colorBody = $colorData['color-body'] ?? '#fff';
        $textColor = self::contrastTextForGradient([$colorBody, $colorTwo, $colorOne]);

        return sprintf(
            '--theme-c1:%s;--theme-c2:%s;--theme-c3:%s;--theme-tx:%s;',
            $colorOne,
            $colorTwo,
            $colorBody,
            $textColor
        );
    }
}

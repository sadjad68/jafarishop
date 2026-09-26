<?php

namespace App\Database\Seeders;

use App\Modules\Banner\Entities\Banner;
use App\Modules\Banner\Entities\Highlight;
use App\Modules\General\Helper\CacheHelper;
use App\Modules\General\Helper\ThemeProvider;
use App\Modules\Tag\Entities\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Manual-only: fills every homepage banner slot from public/assets/site/images
 * (matched by size). Do not call from DatabaseSeeder / ShopSeeder.
 */
class HighlightHomepageSeeder extends Seeder
{
    /** @var array<string, int> */
    private $sourceUsage = [];

    public function run()
    {
        $destDir = public_path('uploads/banner/big');
        File::ensureDirectoryExists($destDir);

        $catalog = $this->sourceCatalog();
        if (count($catalog) === 0) {
            $this->command?->error('No source photos found in public/assets/site/images');
            return;
        }

        $manager = new ImageManager(new Driver());
        $shopLink = url('/all-products');

        $this->fillSliderBanners($manager, $catalog, $destDir);

        $places = config('banner.devices.desktop.place.values', []);
        foreach ($places as $faTitle => $spec) {
            $place = (string) ($spec['key'] ?? '');
            if ($place === '') {
                continue;
            }
            $width = (int) ($spec['width'] ?? 1000);
            $height = (int) ($spec['height'] ?? 350);
            $filename = $this->writeForSlot(
                $manager,
                $this->pickSource($catalog, $width, $height),
                $destDir,
                $width,
                $height
            );
            $this->upsertPlace($place, 'desktop', $faTitle, $filename, $shopLink);
        }

        $tags = Tag::query()
            ->where('show_in_first_page', 1)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        $tagSize = \App\Modules\Banner\Services\HighlightService::tagBannerSize();
        $tagW = (int) ($tagSize['width'] ?? 1400);
        $tagH = (int) ($tagSize['height'] ?? 308);
        foreach ($tags as $tag) {
            $filename = $this->writeForSlot(
                $manager,
                $this->pickSource($catalog, $tagW, $tagH),
                $destDir,
                $tagW,
                $tagH
            );
            $this->upsertTag($tag, 'desktop', $filename);
        }

        CacheHelper::clearCache();
        $this->command?->info(
            'Homepage banners filled from site/images: sliders + highlight places + ' . $tags->count() . ' tags.'
        );
    }

    /**
     * @param  list<array{path: string, w: int, h: int, pool: string}>  $catalog
     */
    private function fillSliderBanners(ImageManager $manager, array $catalog, string $destDir): void
    {
        $sizes = app(ThemeProvider::class)->getSliderSizes();
        $desktopW = (int) ($sizes['desktop']['width'] ?? 1900);
        $desktopH = (int) ($sizes['desktop']['height'] ?? 415);
        $mobileW = (int) ($sizes['mobile']['width'] ?? 700);
        $mobileH = (int) ($sizes['mobile']['height'] ?? 450);

        $banners = Banner::query()->where('show_in_first_page', 1)->orderBy('sort')->get();
        if ($banners->isEmpty()) {
            for ($i = 1; $i <= 3; $i++) {
                $banners->push(Banner::create([
                    'title' => 'اسلایدر صفحه اول ' . $i,
                    'link' => url('/all-products'),
                    'show_in_first_page' => 1,
                    'sort' => $i,
                    'image' => null,
                    'image_mobile' => null,
                ]));
            }
        }

        foreach ($banners as $banner) {
            $banner->update([
                'image' => $this->writeForSlot(
                    $manager,
                    $this->pickSource($catalog, $desktopW, $desktopH),
                    $destDir,
                    $desktopW,
                    $desktopH
                ),
                'image_mobile' => $this->writeForSlot(
                    $manager,
                    $this->pickSource($catalog, $mobileW, $mobileH),
                    $destDir,
                    $mobileW,
                    $mobileH
                ),
            ]);
        }
    }

    /**
     * @return list<array{path: string, w: int, h: int, pool: string}>
     */
    private function sourceCatalog(): array
    {
        $root = public_path('assets/site/images');
        if (! is_dir($root)) {
            return [];
        }

        $skipDirs = ['empty-states', 'icon-app', 'social', 'socials'];
        $skipName = '/(warning|check|checking|lock|mail|avatar|empty|enamad|zarin|sadad|golden|star|off-icon|off-2|offer\.png|cart|logo|profile|phone|brand|cat\d|pro\d|Frame |Map\.|240\.|331\.)/i';

        $catalog = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }
            $path = $file->getPathname();
            $rel = str_replace($root . DIRECTORY_SEPARATOR, '', $path);
            $top = strtolower(explode(DIRECTORY_SEPARATOR, $rel)[0]);
            if (in_array($top, $skipDirs, true)) {
                continue;
            }
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }
            $base = $file->getFilename();
            if (preg_match($skipName, $base)) {
                continue;
            }
            $info = @getimagesize($path);
            if (! $info) {
                continue;
            }
            $w = (int) $info[0];
            $h = (int) $info[1];
            if ($w < 300 || $h < 120) {
                continue;
            }
            if ($h > 0 && ($w / $h) < 0.9 && $w < 500) {
                continue;
            }

            $pool = 'other';
            if (str_starts_with($rel, 'banner' . DIRECTORY_SEPARATOR)) {
                $pool = 'banner';
            } elseif (preg_match('#^shop[/\\\\](banner|header|service)#i', $rel)) {
                $pool = 'shop';
            }

            $catalog[] = [
                'path' => $path,
                'w' => $w,
                'h' => $h,
                'pool' => $pool,
            ];
        }

        return $catalog;
    }

    /**
     * @param  list<array{path: string, w: int, h: int, pool: string}>  $catalog
     */
    private function pickSource(array $catalog, int $width, int $height): string
    {
        $targetAspect = $width / max($height, 1);
        $bestPath = $catalog[0]['path'];
        $bestScore = -INF;

        foreach ($catalog as $item) {
            $srcAspect = $item['w'] / max($item['h'], 1);
            $aspectPenalty = abs(log($srcAspect / $targetAspect));
            $coverScale = min($item['w'] / max($width, 1), $item['h'] / max($height, 1));
            $upscalePenalty = $coverScale >= 1 ? 0.0 : ((1 - $coverScale) * 2.5);
            $poolBonus = 0.0;
            if ($item['pool'] === 'banner') {
                $poolBonus = 0.45;
            } elseif ($item['pool'] === 'shop') {
                $poolBonus = 0.32;
            }
            $exactBonus = ($item['w'] === $width && $item['h'] === $height) ? 3.0 : 0.0;
            $usagePenalty = ($this->sourceUsage[$item['path']] ?? 0) * 0.12;
            $score = $exactBonus + $poolBonus - $aspectPenalty - $upscalePenalty - $usagePenalty;
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestPath = $item['path'];
            }
        }

        $this->sourceUsage[$bestPath] = ($this->sourceUsage[$bestPath] ?? 0) + 1;

        return $bestPath;
    }

    private function writeForSlot(ImageManager $manager, string $source, string $destDir, int $width, int $height): string
    {
        $info = @getimagesize($source);
        $srcW = $info ? (int) $info[0] : 0;
        $srcH = $info ? (int) $info[1] : 0;
        if ($srcW === $width && $srcH === $height) {
            $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
            if ($ext === 'jpeg') {
                $ext = 'jpg';
            }
            $filename = md5(uniqid((string) mt_rand(), true)) . '.' . $ext;
            copy($source, $destDir . '/' . $filename);

            return $filename;
        }

        $filename = md5(uniqid((string) mt_rand(), true)) . '.webp';
        $manager->read($source)
            ->cover(max(1, $width), max(1, $height))
            ->toWebp(88)
            ->save($destDir . '/' . $filename);

        return $filename;
    }

    private function upsertPlace(string $place, string $type, string $title, string $filename, string $link): void
    {
        $keep = Highlight::query()
            ->where('target', 'place')
            ->where('place', $place)
            ->orderByRaw("CASE WHEN type = 'desktop' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->first();

        $payload = [
            'title' => $title,
            'type' => $type,
            'target' => 'place',
            'tag_id' => null,
            'place' => $place,
            'link' => $link,
            'image' => $filename,
            'show_in_first_page' => 1,
        ];

        if ($keep) {
            $keep->update($payload);
        } else {
            $keep = Highlight::create($payload);
        }

        Highlight::query()
            ->where('target', 'place')
            ->where('place', $place)
            ->where('id', '!=', $keep->id)
            ->delete();
    }

    private function upsertTag(Tag $tag, string $type, string $filename): void
    {
        $keep = Highlight::query()
            ->where('target', 'tag')
            ->where('tag_id', $tag->id)
            ->orderByRaw("CASE WHEN type = 'desktop' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->first();

        $payload = [
            'title' => 'بنر تگ «' . $tag->title . '»',
            'type' => $type,
            'target' => 'tag',
            'tag_id' => $tag->id,
            'place' => null,
            'link' => route('tag.detail', ['url' => $tag->url]),
            'image' => $filename,
            'show_in_first_page' => 1,
        ];

        if ($keep) {
            $keep->update($payload);
        } else {
            $keep = Highlight::create($payload);
        }

        Highlight::query()
            ->where('target', 'tag')
            ->where('tag_id', $tag->id)
            ->where('id', '!=', $keep->id)
            ->delete();
    }
}

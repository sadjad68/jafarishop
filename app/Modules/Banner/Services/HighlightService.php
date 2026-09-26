<?php

namespace App\Modules\Banner\Services;

use App\Modules\Banner\DTO\HighlightDTO;
use App\Modules\Banner\Entities\Highlight;
use App\Modules\General\Helper\FileUploader;
use Illuminate\Support\Collection;

class HighlightService
{
    public function create(HighlightDTO $bannerDTO)
    {
        $image = null;
        if ($bannerDTO->getImage()) {
            $uploader = new FileUploader($bannerDTO->getImage(), "uploads/banner");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setQuality(90);
            $uploader->setSizes(["big" => [$bannerDTO->getWidth(), $bannerDTO->getHeight()]]);
            $image = $uploader->upload();
        }
        Highlight::create([
            'image' => $image,
            'title' => $bannerDTO->getTitle(),
            'type' => 'desktop',
            'target' => $bannerDTO->getTarget(),
            'tag_id' => $bannerDTO->getTagId(),
            'place' => $bannerDTO->getPlace(),
            'link' => $bannerDTO->getLink(),
            'show_in_first_page' => $bannerDTO->getShowInFirstPage(),
        ]);
    }

    public function update(int $id, HighlightDTO $bannerDTO)
    {
        $banner = Highlight::findOrfail($id);
        $image = $banner->getRawOriginal('image');
        if ($bannerDTO->getImage()) {
            $uploader = new FileUploader($bannerDTO->getImage(), "uploads/banner");
            $uploader->setExtensions(["jpeg", "webp", "png", "jpg"]);
            $uploader->setSizes(["big" => [$bannerDTO->getWidth(), $bannerDTO->getHeight()]]);
            $uploader->setQuality(90);
            $image = $uploader->upload();
        }

        $banner->update([
            'title' => $bannerDTO->getTitle(),
            'type' => 'desktop',
            'target' => $bannerDTO->getTarget(),
            'tag_id' => $bannerDTO->getTagId(),
            'place' => $bannerDTO->getPlace(),
            'link' => $bannerDTO->getLink(),
            'image' => $image,
            'show_in_first_page' => $bannerDTO->getShowInFirstPage(),
        ]);
    }

    public function destroy(int $id)
    {
        Highlight::destroy($id);
    }

    public static function findAll($query, $limit = 10)
    {
        $data = Highlight::query();
        if (isset($query['first_page'])) {
            $data->firstPage();
        }
        return $data->orderByDesc('id')->take($limit)->get();
    }

    /**
     * موبایل و دسکتاپ از یک بنر می‌خوانند؛ type نادیده گرفته می‌شود.
     *
     * @return array{placeMap: array<string, array<string, Highlight>>, tagMap: array<int, array{mobile: Collection, desktop: Collection}>}
     */
    public static function splitForFirstPage(iterable $highlights): array
    {
        $places = [];
        $tags = [];

        foreach ($highlights as $h) {
            $targetRaw = $h->getAttributes()['target'] ?? null;
            $target = strtolower(trim((string) ($targetRaw ?? 'place')));
            if ($target === '') {
                $target = 'place';
            }

            if ($target === 'tag' && $h->tag_id) {
                $tagId = (int) $h->tag_id;
                if (! isset($tags[$tagId]) || self::preferOverCurrent($h, $tags[$tagId])) {
                    $tags[$tagId] = $h;
                }
                continue;
            }

            if ($h->place) {
                $place = (string) $h->place;
                if (! isset($places[$place]) || self::preferOverCurrent($h, $places[$place])) {
                    $places[$place] = $h;
                }
            }
        }

        $tagMap = [];
        foreach ($tags as $tagId => $highlight) {
            $list = collect([$highlight]);
            $tagMap[$tagId] = [
                'desktop' => $list,
                'mobile' => $list,
            ];
        }

        return [
            'placeMap' => [
                'desktop' => $places,
                'mobile' => $places,
            ],
            'tagMap' => $tagMap,
        ];
    }

    private static function preferOverCurrent(Highlight $candidate, Highlight $current): bool
    {
        $candidateType = strtolower(trim((string) ($candidate->type ?? '')));
        $currentType = strtolower(trim((string) ($current->type ?? '')));

        return $candidateType === 'desktop' && $currentType !== 'desktop';
    }

    public static function highlightHasStoredImage($h): bool
    {
        if (! $h instanceof Highlight) {
            return false;
        }
        $raw = $h->getRawOriginal('image');

        return is_string($raw) && trim($raw) !== '';
    }

    /**
     * سایز بنر زیر تگ‌های صفحه اول (همان بنر عریض تکی).
     *
     * @return array{width: int, height: int}
     */
    public static function tagBannerSize(): array
    {
        $solo = config('banner.devices.desktop.place.values', [])['بنر تکی زیر اسلایدر (تم ۲)'] ?? [];
        $configured = config('banner.tag_banner', []);

        return [
            'width' => (int) ($configured['width'] ?? $solo['width'] ?? 1400),
            'height' => (int) ($configured['height'] ?? $solo['height'] ?? 308),
        ];
    }

    /**
     * @return list<array{row_class: string, places: list<string>}>
     */
    public static function firstPageMainBannerRowDefinitions(): array
    {
        return [
            [
                'row_class' => 'row row-cols-2 row-cols-lg-4 g-2 g-md-3 my-4',
                'places' => ['top-first', 'top-second', 'top-third', 'top-fourth'],
            ],
            [
                'row_class' => 'row row-cols-1 row-cols-lg-2 pt-3',
                'places' => ['middle-first', 'middle-second'],
            ],
        ];
    }

    /**
     * @return list<array{place: string, col_class: string}>
     */
    public static function firstPageBottomBannerSlotDefinitions(): array
    {
        return [
            [
                'place' => 'bottom-first',
                'col_class' => 'col-12 col-md-3 p-2',
            ],
            [
                'place' => 'bottom-second',
                'col_class' => 'col-12 col-md-3 p-2',
            ],
            [
                'place' => 'bottom-third',
                'col_class' => 'col-12 col-md-6 p-2',
            ],
        ];
    }

    /**
     * @return list<array{row_class: string, places: list<string>, tile_col_class: string}>
     */
    public static function firstPageEndBannerRowDefinitions(): array
    {
        return [
            [
                'row_class' => 'row m-0',
                'places' => ['end-full'],
                'tile_col_class' => 'col-12 p-2',
            ],
            [
                'row_class' => 'row m-0',
                'places' => ['end-half-first', 'end-half-second'],
                'tile_col_class' => 'col-md-6 col-12 p-2',
            ],
            [
                'row_class' => 'row m-0',
                'places' => ['end-quad-first', 'end-quad-second', 'end-quad-third', 'end-quad-fourth'],
                'tile_col_class' => 'col-lg-3 col-6 p-2',
            ],
            [
                'row_class' => 'row m-0',
                'places' => ['end-triple-first', 'end-triple-second', 'end-triple-third'],
                'tile_col_class' => 'col-md-4 col-12 p-2',
            ],
        ];
    }

    /**
     * @param  list<array{place: string, wrapper_class: string}>  $slots
     */
    public static function slottedPlacesHaveAnyHighlight(array $placeMap, string $device, array $slots): bool
    {
        foreach ($slots as $slot) {
            $h = $placeMap[$device][$slot['place']] ?? null;
            if (self::highlightHasStoredImage($h)) {
                return true;
            }
        }

        return false;
    }

    /** @return list<array{place: string, wrapper_class: string}> */
    public static function firstPageTheme1TripleSlots(): array
    {
        return [
            ['place' => 'theme1-triple-first', 'wrapper_class' => 'col-lg-4 col-6 p-lg-2 p-1'],
            ['place' => 'theme1-triple-second', 'wrapper_class' => 'col-lg-4 col-6 p-lg-2 p-1'],
            ['place' => 'theme1-triple-third', 'wrapper_class' => 'col-lg-4 col-12 p-lg-2 p-1'],
        ];
    }

    /** @return list<array{place: string, wrapper_class: string}> */
    public static function firstPageTheme1DoubleSlots(): array
    {
        return [
            ['place' => 'theme1-double-first', 'wrapper_class' => 'col-md col-12 p-1'],
            ['place' => 'theme1-double-second', 'wrapper_class' => 'col-md col-12 p-1'],
        ];
    }

    /** @return list<array{place: string, wrapper_class: string}> */
    public static function firstPageTheme1WideSlots(): array
    {
        return [
            ['place' => 'theme1-wide', 'wrapper_class' => 'col p-1'],
        ];
    }

    /** @return list<array{place: string, wrapper_class: string}> */
    public static function firstPageAuxTwoColSlots(): array
    {
        return [
            ['place' => 'aux-two-first', 'wrapper_class' => 'col mb-2 mb-lg-0'],
            ['place' => 'aux-two-second', 'wrapper_class' => 'col'],
        ];
    }

    /** @return list<array{place: string, wrapper_class: string}> */
    public static function firstPageAuxStripSlots(): array
    {
        return [
            ['place' => 'aux-strip', 'wrapper_class' => 'col-12'],
        ];
    }

    public static function rowHasAnyHighlight(array $placeMap, string $device, array $placeKeys): bool
    {
        foreach ($placeKeys as $key) {
            $h = $placeMap[$device][$key] ?? null;
            if (self::highlightHasStoredImage($h)) {
                return true;
            }
        }

        return false;
    }

    public static function bottomRowHasAnyHighlight(array $placeMap, string $device, array $slotDefs): bool
    {
        foreach ($slotDefs as $def) {
            $h = $placeMap[$device][$def['place']] ?? null;
            if (self::highlightHasStoredImage($h)) {
                return true;
            }
        }

        return false;
    }
}

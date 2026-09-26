<?php

namespace Tests\Unit;

use App\Modules\Banner\Entities\Highlight;
use App\Modules\Banner\Services\HighlightService;
use Tests\TestCase;

class HighlightServiceTest extends TestCase
{
    private function highlight(array $attrs): Highlight
    {
        $model = new Highlight();
        $model->setRawAttributes($attrs, true);

        return $model;
    }

    public function test_split_maps_place_and_tag_targets(): void
    {
        $place = $this->highlight([
            'type' => 'desktop',
            'target' => 'place',
            'place' => 'top-first',
            'image' => 'a.webp',
            'tag_id' => null,
        ]);
        $tag = $this->highlight([
            'type' => 'desktop',
            'target' => 'tag',
            'place' => null,
            'image' => 'b.webp',
            'tag_id' => 7,
        ]);

        $split = HighlightService::splitForFirstPage([$place, $tag]);

        $this->assertSame($place, $split['placeMap']['desktop']['top-first']);
        $this->assertSame($place, $split['placeMap']['mobile']['top-first']);
        $this->assertCount(1, $split['tagMap'][7]['desktop']);
        $this->assertSame($split['tagMap'][7]['desktop'], $split['tagMap'][7]['mobile']);
    }

    public function test_mobile_place_fills_missing_desktop_slot(): void
    {
        $mobile = $this->highlight([
            'type' => 'mobile',
            'target' => 'place',
            'place' => 'solo',
            'image' => 'm.webp',
        ]);
        $desktop = $this->highlight([
            'type' => 'desktop',
            'target' => 'place',
            'place' => 'top-first',
            'image' => 'd.webp',
        ]);

        $split = HighlightService::splitForFirstPage([$mobile, $desktop]);

        $this->assertSame($desktop, $split['placeMap']['desktop']['top-first']);
        $this->assertSame($mobile, $split['placeMap']['desktop']['solo']);
        $this->assertSame($mobile, $split['placeMap']['mobile']['solo']);
        $this->assertSame($desktop, $split['placeMap']['mobile']['top-first']);
    }

    public function test_desktop_slot_is_not_overwritten_by_mobile(): void
    {
        $desktop = $this->highlight([
            'type' => 'desktop',
            'target' => 'place',
            'place' => 'solo',
            'image' => 'd.webp',
        ]);
        $mobile = $this->highlight([
            'type' => 'mobile',
            'target' => 'place',
            'place' => 'solo',
            'image' => 'm.webp',
        ]);

        $split = HighlightService::splitForFirstPage([$desktop, $mobile]);

        $this->assertSame($desktop, $split['placeMap']['desktop']['solo']);
        $this->assertSame($desktop, $split['placeMap']['mobile']['solo']);
    }

    public function test_highlight_has_stored_image(): void
    {
        $withImage = $this->highlight(['image' => 'x.webp']);
        $empty = $this->highlight(['image' => '']);
        $null = $this->highlight(['image' => null]);

        $this->assertTrue(HighlightService::highlightHasStoredImage($withImage));
        $this->assertFalse(HighlightService::highlightHasStoredImage($empty));
        $this->assertFalse(HighlightService::highlightHasStoredImage($null));
        $this->assertFalse(HighlightService::highlightHasStoredImage(null));
    }

    public function test_newer_place_highlight_wins_when_keys_duplicate(): void
    {
        $older = $this->highlight([
            'id' => 1,
            'type' => 'desktop',
            'target' => 'place',
            'place' => 'top-first',
            'image' => 'old.webp',
        ]);
        $newer = $this->highlight([
            'id' => 2,
            'type' => 'desktop',
            'target' => 'place',
            'place' => 'top-first',
            'image' => 'new.webp',
        ]);

        $split = HighlightService::splitForFirstPage([$newer, $older]);

        $this->assertSame($newer, $split['placeMap']['desktop']['top-first']);
        $this->assertSame($newer, $split['placeMap']['mobile']['top-first']);
    }

    public function test_row_definitions_match_partofix_slots(): void
    {
        $this->assertSame(
            ['top-first', 'top-second', 'top-third', 'top-fourth'],
            HighlightService::firstPageMainBannerRowDefinitions()[0]['places']
        );
        $this->assertSame(
            ['middle-first', 'middle-second'],
            HighlightService::firstPageMainBannerRowDefinitions()[1]['places']
        );
        $this->assertSame('bottom-third', HighlightService::firstPageBottomBannerSlotDefinitions()[2]['place']);
        $this->assertSame(['end-full'], HighlightService::firstPageEndBannerRowDefinitions()[0]['places']);
        $this->assertSame(1400, HighlightService::tagBannerSize()['width']);
        $this->assertSame(308, HighlightService::tagBannerSize()['height']);
    }
}

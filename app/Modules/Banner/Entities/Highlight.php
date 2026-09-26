<?php

namespace App\Modules\Banner\Entities;

use App\Modules\General\Helper\FileManager;
use App\Modules\General\Traits\GlobalScopesTrait;
use App\Modules\Tag\Entities\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notifiable;

class Highlight extends Model
{
    use Notifiable;
    use SoftDeletes;
    use GlobalScopesTrait;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'show_in_first_page', 'title', 'image', 'place',
        'link', 'type', 'target', 'tag_id',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function isTagTarget(): bool
    {
        return ($this->attributes['target'] ?? 'place') === 'tag';
    }

    public function adminDeviceLabel(): string
    {
        return ($this->attributes['type'] ?? '') === 'mobile' ? 'موبایل' : 'دسکتاپ';
    }

    /** عنوان تگ برای لیست ادمین (فقط حالت target=tag). */
    public function adminTagTitle(): string
    {
        if (! $this->isTagTarget()) {
            return '';
        }
        if ($this->relationLoaded('tag') && $this->tag) {
            return (string) $this->tag->title;
        }
        $title = $this->attributes['tag_id']
            ? Tag::query()->whereKey($this->attributes['tag_id'])->value('title')
            : null;

        return $title !== null && $title !== '' ? (string) $title : '—';
    }

    /**
     * عنوان فارسی اسلات همانند فرم ادمین (از config)، فقط برای target=place.
     */
    public function adminPlaceSlotTitleFromConfig(): string
    {
        if ($this->isTagTarget()) {
            return '';
        }
        $type = 'desktop';
        $place = (string) ($this->attributes['place'] ?? '');
        if ($place === '') {
            return '—';
        }
        $values = Config::get('banner.devices.desktop.place.values', []);
        foreach ($values as $faTitle => $spec) {
            if (($spec['key'] ?? '') === $place) {
                return $faTitle;
            }
        }
        $mobileValues = Config::get('banner.devices.mobile.place.values', []);
        foreach ($mobileValues as $faTitle => $spec) {
            if (($spec['key'] ?? '') === $place) {
                return $faTitle;
            }
        }

        return 'جایگاه نامشخص';
    }

    /** توضیح بخش کلی صفحه برای جایگاه (بدون کلید فنی). */
    public function adminPlaceZoneDescription(): string
    {
        if ($this->isTagTarget()) {
            return '';
        }
        $place = (string) ($this->attributes['place'] ?? '');
        if ($place === '') {
            return 'صفحهٔ اول';
        }
        if (str_starts_with($place, 'top-')) {
            return 'ردیف بنرهای بالای صفحهٔ اول';
        }
        if (str_starts_with($place, 'middle-')) {
            return 'ردیف بنرهای وسط صفحهٔ اول';
        }
        if (str_starts_with($place, 'bottom-')) {
            return 'ردیف بنرهای پایین صفحهٔ اول';
        }
        if (str_starts_with($place, 'end-')) {
            return 'بنرهای انتهای صفحهٔ اول';
        }
        if ($place === 'solo') {
            return 'بنر تکی زیر اسلایدر';
        }
        if (str_starts_with($place, 'aux-')) {
            return 'بنر اختیاری صفحهٔ اول';
        }
        if (str_starts_with($place, 'theme1-')) {
            return 'بنر تم لومیرا — صفحهٔ اول';
        }

        return 'صفحهٔ اول';
    }

    public function getImageAttribute()
    {
        $filename = $this->attributes['image'] ?? null;
        if (! is_string($filename) || trim($filename) === '') {
            return asset('assets/notfounds/default.jpg');
        }

        return FileManager::serveFile(
            'uploads/banner/big/' . $filename,
            'assets/notfounds/default.jpg'
        );
    }

    public function getFirstPageNameAttribute()
    {
        return $this->attributes['show_in_first_page'] == 1 ?
            ['title' => 'نمایش در صفحه اول', 'badge' => 'success']
            :
            ['title' => 'عدم نمایش در صفحه اول', 'badge' => 'danger'];
    }

    public function getDevicePlacement($type, $place)
    {
        if (($this->attributes['target'] ?? 'place') === 'tag') {
            if (empty($this->attributes['tag_id'])) {
                return 'تگ';
            }
            $tagTitle = $this->relationLoaded('tag')
                ? ($this->tag->title ?? '')
                : (Tag::query()->whereKey($this->attributes['tag_id'])->value('title') ?? '');

            return trim('تگ: ' . $tagTitle);
        }

        if ($place !== '') {
            $values = Config::get('banner.devices.desktop.place.values', []);
            foreach ($values as $faTitle => $spec) {
                if (($spec['key'] ?? '') === $place) {
                    return $faTitle;
                }
            }
        }

        return 'نامشخص';
    }
}

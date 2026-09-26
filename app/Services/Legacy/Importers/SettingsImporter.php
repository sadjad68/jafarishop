<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;
use Illuminate\Support\Facades\Config;

class SettingsImporter
{
    /**
     * جایگاه‌های صفحهٔ اول فروشگاه (theme2) در جدول highlights.
     * بنرهای تنظیمات قدیمی اسلایدر نیستند.
     */
    private const HIGHLIGHTS = [
        'banner8' => ['place' => 'solo', 'title' => 'بنر پایین اسلایدر', 'link' => 'banner8_link'],
        'banner1' => ['place' => 'top-first', 'title' => 'بنر کنار اسلایدر', 'link' => 'banner1_link'],
        'banner2' => ['place' => 'top-second', 'title' => 'بنر دوم', 'link' => 'banner2_link'],
        'banner3' => ['place' => 'top-third', 'title' => 'بنر سوم', 'link' => 'banner3_link'],
        'banner4' => ['place' => 'top-fourth', 'title' => 'بنر لوازم یدکی', 'link' => 'banner4_link'],
        'bannerright' => ['place' => 'aux-two-first', 'title' => 'بنر سمت راست', 'link' => 'bannerright'],
        'bennerleft' => ['place' => 'aux-two-second', 'title' => 'بنر سمت چپ', 'link' => 'bennerleft'],
        'banner5' => ['place' => 'end-triple-first', 'title' => 'واتساپ', 'link' => 'whatsapp_link'],
        'banner7' => ['place' => 'end-triple-second', 'title' => 'اینستاگرام', 'link' => 'instagram_link'],
        'banner6' => ['place' => 'end-triple-third', 'title' => 'تلگرام', 'link' => 'telegram_link'],
    ];

    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $this->ensureStructure($stats);

        $setting = $this->support->old()->table('settings')->orderBy('id')->first();
        if ($setting) {
            $this->applyValues($setting);
            $this->applyHomeSeo($setting, $stats);
            $this->applySocials($setting, $stats);
            $this->highlights($setting, $stats);
            $this->support->mark('settings', (int) $setting->id);
            $stats->inserted++;
        }

        $this->sliders($stats);
        $this->support->realignAutoIncrement('banners');
        $this->support->realignAutoIncrement('highlights');
        $this->support->realignAutoIncrement('socials');
        $this->support->realignAutoIncrement('seo_metas');
        $this->support->realignAutoIncrement('settings');

        return $stats;
    }

    private function ensureStructure(LegacyImportStats $stats): void
    {
        $partials = Config::get('setting_structure.setting_partials', []);
        if (!is_array($partials)) {
            return;
        }
        $this->walkPartials($partials, $stats);
    }

    private function walkPartials(array $partials, LegacyImportStats $stats): void
    {
        foreach ($partials as $partial) {
            foreach ($partial['fields'] ?? [] as $field) {
                $this->ensureField($field, $stats);
            }
            if (!empty($partial['partials']) && is_array($partial['partials'])) {
                $this->walkPartials($partial['partials'], $stats);
            }
        }
    }

    private function ensureField(array $field, LegacyImportStats $stats): void
    {
        $key = (string) ($field['key'] ?? '');
        if ($key === '') {
            return;
        }
        $existing = $this->support->new()->table('settings')->where('key', $key)->first();
        $now = date('Y-m-d H:i:s');
        $options = $field['options'] ?? null;
        $meta = [
            'p_name' => $field['p_name'] ?? $key,
            'type' => $field['type'] ?? 'text',
            'theme_type' => $field['theme_type'] ?? null,
            'options' => is_array($options) ? json_encode($options, JSON_UNESCAPED_UNICODE) : $options,
            'updated_at' => $now,
        ];
        if ($existing) {
            $this->support->new()->table('settings')->where('id', $existing->id)->update($meta);
            $stats->skipped++;

            return;
        }
        $this->support->new()->table('settings')->insert($meta + [
            'key' => $key,
            'value' => $this->structureValue($field),
            'created_at' => $now,
        ]);
        $stats->inserted++;
    }

    private function structureValue(array $field): ?string
    {
        $value = $field['value'] ?? null;
        $type = (string) ($field['type'] ?? '');
        if (in_array($type, ['menu', 'work_hours', 'footer', 'select'], true) || is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    private function applyValues(object $setting): void
    {
        $phones = LegacyMapper::phoneNumbers($setting->phone ?? null);
        $main = null;
        foreach ($phones as $phone) {
            if (str_starts_with($phone, '0') && !str_starts_with($phone, '09')) {
                $main = $phone;
                break;
            }
        }
        if ($main === null && isset($phones[0])) {
            $main = $phones[0];
        }

        $values = [
            'theme' => 'theme2',
            'address' => LegacyMapper::combineText($setting->address ?? null),
            'main_phone_number' => $main,
            'phone_numbers' => $phones === [] ? null : implode('~~##', $phones),
            'all_product_description' => LegacyMapper::combineText($setting->product_description ?? null),
            'siteName_fa' => LegacyMapper::siteNameFromSeoTitle($setting->title_seo ?? null),
        ];

        $whatsapp = trim((string) ($setting->whatsapp_link ?? ''));
        if ($whatsapp !== '') {
            $values['share_type'] = 'whatsapp';
            $values['share_button_link'] = $whatsapp;
            $values['share_button_number'] = LegacyMapper::whatsappNumber($whatsapp);
            $values['show_share_button'] = '1';
        }

        $now = date('Y-m-d H:i:s');
        foreach ($values as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $this->support->new()->table('settings')->where('key', $key)->update([
                'value' => $value,
                'updated_at' => $now,
            ]);
        }
    }

    private function applyHomeSeo(object $setting, LegacyImportStats $stats): void
    {
        $title = LegacyMapper::combineText($setting->title_seo ?? null);
        $description = LegacyMapper::combineText($setting->description_seo ?? null);
        if ($title === null && $description === null) {
            return;
        }
        $existing = $this->support->new()->table('seo_metas')->where('url', '/')->whereNull('deleted_at')->first();
        $now = date('Y-m-d H:i:s');
        if ($existing) {
            $this->support->new()->table('seo_metas')->where('id', $existing->id)->update([
                'title_seo' => $title,
                'description_seo' => $description,
                'noindex' => 0,
                'updated_at' => $now,
            ]);
            $stats->skipped++;

            return;
        }
        $this->support->insertNew('seo_metas', [
            'seoable_type' => null,
            'seoable_id' => null,
            'title_seo' => $title,
            'description_seo' => $description,
            'url' => '/',
            'noindex' => 0,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ], $stats);
    }

    private function applySocials(object $setting, LegacyImportStats $stats): void
    {
        $links = [
            'instagram' => $setting->instagram_link ?? null,
            'telegram' => $setting->telegram_link ?? null,
            'whatsapp' => $setting->whatsapp_link ?? null,
        ];
        $now = date('Y-m-d H:i:s');
        foreach ($links as $icon => $link) {
            $link = trim((string) $link);
            if ($link === '') {
                continue;
            }
            $existing = $this->support->new()->table('socials')->where('icon', $icon)->orderBy('id')->first();
            if ($existing) {
                $this->support->new()->table('socials')->where('id', $existing->id)->update([
                    'link' => $link,
                    'deleted_at' => null,
                    'updated_at' => $now,
                ]);
                $stats->skipped++;
                continue;
            }
            $this->support->insertNew('socials', [
                'icon' => $icon,
                'link' => $link,
                'is_app_icon' => 0,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ], $stats);
        }
    }

    private function highlights(object $setting, LegacyImportStats $stats): void
    {
        $now = date('Y-m-d H:i:s');
        $this->support->new()->table('banners')
            ->whereIn('place', array_keys(self::HIGHLIGHTS))
            ->whereNull('deleted_at')
            ->update([
                'show_in_first_page' => 0,
                'deleted_at' => $now,
                'updated_at' => $now,
            ]);

        $media = $this->support->old()->table('media')
            ->where('model_type', 'App\\Models\\Setting')
            ->orderBy('id')
            ->get(['id', 'collection_name']);

        foreach ($media as $row) {
            $collection = (string) $row->collection_name;
            if (!isset(self::HIGHLIGHTS[$collection])) {
                continue;
            }
            $spec = self::HIGHLIGHTS[$collection];
            $filename = LegacyMapper::mediaFilename((int) $row->id);
            $linkColumn = $spec['link'];
            $payload = [
                'title' => $spec['title'],
                'link' => LegacyMapper::siteLink($setting->{$linkColumn} ?? null),
                'image' => $filename,
                'type' => 'desktop',
                'target' => 'place',
                'tag_id' => null,
                'place' => $spec['place'],
                'show_in_first_page' => 1,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
            $existing = $this->support->new()->table('highlights')
                ->where('place', $spec['place'])
                ->where('type', 'desktop')
                ->orderBy('id')
                ->first();
            if ($existing) {
                $this->support->new()->table('highlights')->where('id', $existing->id)->update($payload);
                $stats->skipped++;
                continue;
            }
            $payload['created_at'] = $now;
            $this->support->insertNew('highlights', $payload, $stats);
        }
    }

    private function sliders(LegacyImportStats $stats): void
    {
        $rows = $this->support->old()->table('sliders')->orderBy('id')->get();
        $now = date('Y-m-d H:i:s');
        foreach ($rows as $row) {
            $id = (int) $row->id;
            $media = $this->support->old()->table('media')
                ->where('model_type', 'App\\Models\\Slider')
                ->where('model_id', $id)
                ->orderBy('id')
                ->first();
            $filename = $media ? LegacyMapper::mediaFilename((int) $media->id) : null;
            $payload = [
                'link' => LegacyMapper::siteLink($row->link ?? null),
                'image' => $filename,
                'type' => 'desktop',
                'place' => null,
                'show_in_first_page' => (int) ($row->status ?? 0) === 1 ? 1 : 0,
                'title' => $row->title ?? null,
                'sort' => $id,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ];
            if ($this->support->targetExists('banners', $id)) {
                $this->support->new()->table('banners')->where('id', $id)->update($payload);
                $this->support->mark('sliders', $id);
                $stats->skipped++;
                continue;
            }
            $payload['id'] = $id;
            $payload['created_at'] = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->new()->table('banners')->insert($payload);
            $this->support->mark('sliders', $id);
            $stats->inserted++;
        }
    }
}

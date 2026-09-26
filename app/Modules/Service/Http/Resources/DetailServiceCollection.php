<?php

namespace App\Modules\Service\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DetailServiceCollection extends ResourceCollection
{
    protected $hiddenFields = [];

    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return $this->transformItemToArray($item);
        })->toArray();
    }
//Todo : جابجایی unset
    public function transformItemToArray($item)
    {
        $setting_service_header = Setting::where('key','service_header')->first();
        $setting_main_number = Setting::where('key','main_phone_number')->first();
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'image' => $item->image,
            'parent' => $item->parent,
            'parent_url' => @$item->parent->url,
            'url' => $item->url,
            'children' => $item->children,
            'header_image' => $item->getRawOriginal('header_image') ? $item->header_image : self::fileFormat($setting_service_header['value']),
            'phone_number' => $item->phone_number ? $item->phone_number : $setting_main_number['value'],
            'short_description' => $item->short_description,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'title_seo' => $item->seoTitle ? $item->seoTitle : $item->title,
            'description_seo' => $item->seoDescription,
            'noindex' => $item->seoIndex,
        ];

        foreach ($this->hiddenFields as $field) {
            unset($data[$field]);
        }

        return $data;
    }

    public function setHiddenFields(array $fields)
    {
        $this->hiddenFields = $fields;
        return $this;
    }
    private function fileFormat($value): string
    {
        return FileManager::serveFile(
            'uploads/setting/' . $value, 'assets/notfounds/service-header-detail.jpg'        );
    }
}



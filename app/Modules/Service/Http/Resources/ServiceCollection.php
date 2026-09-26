<?php

namespace App\Modules\Service\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
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
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'short_description' => $item->short_description,
            'image' => $item->image,
            'header_image' => $item->getRawOriginal('header_image') ? $item->header_image : self::fileFormat($setting_service_header['value']),
            'parent' => $item->parent,
            'parent_url' => @$item->parent->url,
            'url' => $item->url,
            'children' => $item->children,
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
            'uploads/setting/' . $value, 'assets/notfounds/service-header-detail.jpg'
        );
    }
}



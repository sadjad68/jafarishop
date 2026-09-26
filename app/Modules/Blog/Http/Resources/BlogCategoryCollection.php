<?php

namespace App\Modules\Blog\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCategoryCollection extends ResourceCollection
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
        $setting_service_header = Setting::where('key','blog_list_header')->first();
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'image' => $item->item_image,
            'url' => $item->url,
            'title_seo' => $item->seoTitle ? $item->seoTitle : $item->title,
            'description_seo' => $item->seoDescription,
            'noindex' => $item->seoIndex,
            'header_image' =>  self::fileFormat($setting_service_header['value']),
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
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



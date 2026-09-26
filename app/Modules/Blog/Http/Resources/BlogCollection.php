<?php

namespace App\Modules\Blog\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCollection extends ResourceCollection
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
            'blog_category_url' => $item->category->url,
            'blog_category_title' => $item->category->title,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'publish_date' => $item->publish_date,
            'author' => $item->author,
            'header_image' =>  self::fileFormat($setting_service_header['value']),
            'call_to_action' => $item->call_to_action,
            'view' => $item->view ? $item->view : 0,
            'reading_time' => $this->getReadingTime($item->description),
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

    function getReadingTime($description)
    {

// طول متن را بدست می‌آوریم
        $text_length = strlen($description);

// میانگین سرعت خواندن فرد در دقیقه (به عنوان مثال 200 کاراکتر در دقیقه)
        $average_reading_speed = 1000;

// مدت زمان خواندن متن را محاسبه می‌کنیم (به صورت دقیقه)
        $reading_time_minutes = $text_length / $average_reading_speed;

// مدت زمان خواندن متن را گرد می‌کنیم به بالا
        $reading_time_minutes = ceil($reading_time_minutes);

      return $reading_time_minutes;

    }
    private function fileFormat($value): string
    {
        return FileManager::serveFile(
            'uploads/setting/' . $value, 'assets/notfounds/blogs-header.jpg'
        );
    }
}



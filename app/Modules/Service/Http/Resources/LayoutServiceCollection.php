<?php

namespace App\Modules\Service\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LayoutServiceCollection extends ResourceCollection
{
    protected $hiddenFields = [];
    protected $childrenHiddenFields = [];

    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return $this->transformItemToArray($item);
        })->toArray();
    }

    public function transformItemToArray($item)
    {
        $setting_service_header = Setting::where('key', 'service_header')->first();
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'image' => $item->image,
            'header_image' => $item->getRawOriginal('header_image') ? $item->header_image : self::fileFormat($setting_service_header['value']),
            'parent' => $item->parent,
            'parent_url' => @$item->parent->url,
            'url' => $item->url,
            'children' => $this->getChildrenToDepth($item, 2), // بازگشتی با عمق 2
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

    public function setChildrenHiddenFields(array $fields)
    {
        $this->childrenHiddenFields = $fields;
        return $this;
    }

    private function fileFormat($value): string
    {
        return FileManager::serveFile('uploads/setting/' . $value, 'assets/notfounds/service-header-detail.jpg');
    }

    private function getChildrenToDepth($item, $depth)
    {
        if ($depth <= 0) {
            return [];
        }

        return $item->children->map(function ($child) use ($depth) {
            $childData = [
                'id' => $child->id,
                'title' => $child->title,
                'description' => $child->description,
                'image' => $child->image,
                'url' => $child->url,
                'children' => $this->getChildrenToDepth($child, $depth - 1), // بازگشتی
            ];

            // حذف فیلدهای پنهان در children
            foreach ($this->childrenHiddenFields as $field) {
                unset($childData[$field]);
            }

            return $childData;
        })->toArray();
    }
}

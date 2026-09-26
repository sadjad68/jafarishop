<?php

namespace App\Modules\Service\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ForFilterServiceCollection extends ResourceCollection
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
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'parent' => $item->parent,
            'parent_url' => @$item->parent->url,
            'children' => $this->getChildrenToDepth($item, 6), // بازگشتی با عمق 6
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


    private function getChildrenToDepth($item, $depth)
    {
        if ($depth <= 0) {
            return [];
        }

        return $item->children->map(function ($child) use ($depth) {
            $childData = [
                'id' => $child->id,
                'title' => $child->title,
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

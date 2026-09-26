<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Product\Entities\Product
     *
     */
    protected $hiddenFields = [];

    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            $item["children"] = collect($item->children)->map(function ($subItem) {
                return $this->transformItemToArray($subItem);
            })->toArray();
            return $item;
        })->toArray();
    }

    public function transformItemToArray($item)
    {
        $data =  [
            'id' => @$item->id,
            'title' => @$item->title,
            'parent' => @$item->parent->title,
            'parent_id' => @$item->parent_id,
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
}

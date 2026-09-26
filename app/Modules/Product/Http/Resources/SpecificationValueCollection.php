<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecificationValueCollection extends ResourceCollection
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
            return collect($item)->map(function ($subItem) {
                return $this->transformItemToArray($subItem);
            })->toArray();
        })->toArray();
    }
//Todo : جابجایی unset
    public function transformItemToArray($item)
    {
        $data =  [
            'id' => @$item->id,
            'value' => @$item->value,
            'specification' => @$item->specification->title,
            'specification_id' => @$item->specification_id,

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

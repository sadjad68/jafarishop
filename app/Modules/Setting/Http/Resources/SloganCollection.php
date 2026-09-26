<?php

namespace App\Modules\Setting\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SloganCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Setting\Entities\Slogan
     *
     */
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

        $data =  [
            'id' => $item->id,
            'value' => $item->value,
            'icon' => $item->image,

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

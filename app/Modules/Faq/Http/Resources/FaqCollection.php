<?php

namespace App\Modules\Faq\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FaqCollection extends ResourceCollection
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
            return $this->transformItemToArray($item);
        })->toArray();
    }
//Todo : جابجایی unset
    public function transformItemToArray($item)
    {

        $data =  [
            'id' => $item->id,
            'question' => $item->question,
            'answer' => $item->answer,

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

<?php

namespace App\Modules\Service\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FeeCollection extends ResourceCollection
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
        $data = [
            'id' => $item->id,
            'description'=>$item->description,
            'minimum_price'=>$item->min_price,
            'maximum_price'=>$item->max_price,
            'service_id'=>$item->service_id,
            'service_title'=>$item->service->title,

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



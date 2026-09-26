<?php

namespace App\Modules\Product\Http\Resources;

use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchedBrandCollection extends ResourceCollection
{

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
            'url' => '/brand/'.$item->url,
        ];

        return $data;
    }


}



<?php

namespace App\Modules\Service\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FirstPageWorkSampleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\App\Modules\Service\Entities\WorkSample
     *
     */

    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return $this->transformItemToArray($item);
        })->toArray();
    }

    public function transformItemToArray($item)
    {

        $data = [
            'id' => @$item->id,
            'title' => @$item->title,
            'url' => @$item->has_page == 1 ?  '/portfolio/' . @$item->url :  '/portfolios',
            'image' => @$item->getImage('medium'),
            'before_image' => $item->double_image == 1 ? $item->item_before_image : null,
        ];

        return $data;
    }

}

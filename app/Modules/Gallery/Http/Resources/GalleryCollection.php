<?php

namespace App\Modules\Gallery\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GalleryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Gallery\Entities\Gallery
     *
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => @$item->id,
                'title' => @$item->title,
                'image' => @$item->item_image,
                'category' => @$item->category->title,
                'description' => @$item->description,
            ];
        });
    }
}

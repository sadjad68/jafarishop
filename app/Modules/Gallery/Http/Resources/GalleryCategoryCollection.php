<?php

namespace App\Modules\Gallery\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GalleryCategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Gallery\Entities\GalleryCategory
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
            'image' => @$item->item_image,
            'url' => @$item->url,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'title_seo' => $item->seoTitle ? $item->seoTitle : $item->title,
            'description_seo' => $item->seoDescription,
            'noindex' => $item->seoIndex,
        ];



        return $data;
    }
}

<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Product\Entities\Product
     *
     */
    protected $hiddenFields = [];
    protected $image_size = "big";

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
            'title' => $item->title,
            'description' => $item->description,
            'url' => $item->url,
            'image' => $item->getImage($this->image_size),
            'title_seo' => $item->seoTitle ? $item->seoTitle : $item->title,
            'description_seo' => $item->seoDescription,
            'noindex' => $item->seoIndex,
            'product_counts'=>$item->products ? count($item->products) : 0,
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
    public function setImageSize(string $size)
    {
        $this->image_size = $size;
    }
}

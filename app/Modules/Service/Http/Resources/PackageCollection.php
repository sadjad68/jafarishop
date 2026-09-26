<?php

namespace App\Modules\Service\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PackageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Service\Entities\Package
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
            'title' => $item->title,
            'description' => $item->description,
            'url' => $item->url,
            'image' => $item->getImage(),
            'price' => intval($item->discounted_price) != 0 ? $item->price_format : 0,
            'discounted_price' => intval($item->discounted_price) != 0 ? $item->discounted_price_format : $item->price_format ,
            'services' => $item->servicesCollection,
            'title_seo' => $item->seoTitle ? $item->seoTitle : $item->title,
            'description_seo' => $item->seoDescription,
            'noindex' => $item->seoIndex,
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

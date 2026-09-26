<?php

namespace App\Modules\Service\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkSampleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\App\Modules\Service\Entities\WorkSample
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

        $data = [
            'id' => @$item->id,
            'title' => @$item->title,
            'description' => @$item->description,
            'short_description' => @$item->short_description,
            'double_image' => @$item->double_image,
            'url' => @$item->has_page == 1 ? '/portfolio/' . @$item->url : null,
            'image' => @$item->getImage('medium'),
            'before_image' => $item->double_image == 1 ? @$item->getBeforeImage('medium') : null,
            'images' => $this->getImages($item),
            'title_seo' => $item->seoTitle ? $item->seoTitle : $item->title,
            'description_seo' => $item->seoDescription,
            'noindex' => $item->seoIndex,
        ];

        foreach ($this->hiddenFields as $field) {
            unset($data[$field]);
        }

        return $data;
    }

    private function getImages($item)
    {

        return $item->imagesCollection->map(function ($image) {
            $imageData = [
                'id' => @$image->id,
                'work_sample_id' => @$image->work_sample_id,
                'image' => @$image->getImage('medium'),
                'image_small' => @$image->getImage('small'),
                'before_image' => @$image->getBeforeImage('medium')
            ];
            return $imageData;
        })->toArray();
    }
    public function setHiddenFields(array $fields)
    {
        $this->hiddenFields = $fields;
        return $this;
    }
}

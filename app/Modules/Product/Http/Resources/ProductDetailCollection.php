<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductDetailCollection extends ResourceCollection
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
            'id' => @$item->id,
            'title' => @$item->title,
            'description' => @$item->description,
            'url' => @$item->url,
            'image' => @$item->getImage('big'),
            'title_seo' => @$item->seoTitle ? @$item->seoTitle : @$item->title,
            'description_seo' => @$item->seoDescription,
            'noindex' => @$item->seoIndex,
            'final_price'=>number_format(@$item->final_price),
            'price'=>intval(@$item->discounted_price) != 0 ? number_format(@$item->price) : null,
            'percent'=> intval(@$item->discounted_price) != 0 ? number_format($this->calculateDiscount(@$item->price, @$item->final_price)) : null,
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
    public function calculateDiscount($originalPrice, $discountedPrice)
    {
        // بررسی اینکه قیمت اولیه نباید صفر یا منفی باشد
        if ($originalPrice <= 0) {
            return 0;
        }

        // محاسبه مقدار تخفیف
        $discountAmount = $originalPrice - $discountedPrice;

        // محاسبه درصد تخفیف
        $discountPercentage = ($discountAmount / $originalPrice) * 100;

        return $discountPercentage;
    }
}

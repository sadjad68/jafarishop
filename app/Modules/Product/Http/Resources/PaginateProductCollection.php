<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginateProductCollection extends ResourceCollection
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
        // این بخش به منظور اضافه کردن اطلاعات صفحه‌بندی به خروجی است
        return [
            'data' => $this->collection->map(function ($item) {
                return $this->transformItemToArray($item);
            })->toArray(),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'last_page' => $this->lastPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
            ],
        ];
    }

    public function transformItemToArray($item)
    {
        $data =  [
            'id' => $item->id,
            'title' => $item->title,
            'url' => $item->url,
            'image' => $item->getImage(),
            'categories' => $item->categories,
            'final_price' => number_format($item->final_price),
            'price' => intval($item->discounted_price) != 0 ? number_format($item->price) : null,
            'percent' => intval($item->discounted_price) != 0 ? number_format($this->calculateDiscount($item->price, $item->final_price)) : '',
            'end_timer' => $item->end_timer,
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

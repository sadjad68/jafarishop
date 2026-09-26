<?php

namespace App\Modules\Product\Http\Resources;

use App\Library\NumberHelper;
use App\Modules\General\Helper\FileManager;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListProductCollection extends ResourceCollection
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
            'image' => $item->getImage(),
            'vue_url' => \App\Library\SiteUrl::product($item, false),
            'stock'=>intval($item->stock),
            'final_price' => NumberHelper::latin2PersianDigit(number_format($item->final_price)),
            'price' => intval($item->discounted_price) != 0 ? NumberHelper::latin2PersianDigit(number_format($item->price)) : null,
            'percent' =>
                intval($item->stock) != 0 ?
                    (intval($item->discounted_price) != 0 ? NumberHelper::latin2PersianDigit((string) round($this->calculateDiscount($item->price, $item->final_price))) : null)
                    : null,
            'has_variants' => $item->hasVariants(),
        ];

        return $data;
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



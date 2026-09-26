<?php

namespace App\Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Modules\Product\Services\VariantService;

class BasketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Order\Entities\Basket
     *
     */
    public function __construct($collection)
    {
        parent::__construct($collection);


    }

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                $item->product?->loadMissing(['categories', 'brand']);
                $final_price_toman = $item->product_variant_id
                    ? (int) ($item->productVariant->final_price ?? 0)
                    : (int) ($item->product->final_price ?? 0);
                $price_toman = $item->product_variant_id
                    ? (int) ($item->productVariant->price ?? 0)
                    : (int) ($item->product->price ?? 0);
                $category_titles = $item->product?->categories
                    ? $item->product->categories->pluck('title')->filter()->values()->take(2)->all()
                    : [];

                return [
                    'success' => true,
                    'id' => @$item->id,
                    'product_title' => @$item->product->title,
                    'product_id' => $item->product_id,
                    'product_image' => @$item->product->getImage(),
                    'product_final_price' => @$item->product_variant_id
                        ? number_format(intval(@$item->productVariant->final_price)) . ' تومان '
                        : number_format(intval(@$item->product->final_price)) . ' تومان ',
                    'product_price' =>$item->product_variant_id ?
                        (intval($item->productVariant->discounted_price) != 0 ? number_format(intval(@$item->productVariant->price)) . ' تومان '  : 0) :
                        (intval($item->product->discounted_price) != 0 ?  number_format(intval(@$item->product->price)) . ' تومان ' : 0),
                    'final_price_toman' => $final_price_toman,
                    'price_toman' => $price_toman,
                    'category_titles' => $category_titles,
                    'main_variant_title' => @$item->product->main_variant_specification_id != null ? @$item->product->mainVariant->title : '',
                    'variant_title' => @$item->product_variant_id ? @$item->productVariant->specification->title : '',
                    'variant_id' => @$item->product_variant_id,
                    'variant_color' => (@$item->product->main_variant_specification_id && @$item->product->mainVariant->is_color == 1) ?
                        @$item->productVariant->specification->color_code : '',
                    'brand_title' => @$item->product->brand->title,
                    'product_url' => \App\Library\SiteUrl::product(@$item->product),
                    'quantity' => @$item->quantity,
                    'percent' => @$item->product_variant_id ? @$item->productVariant->percent : @$item->product->percent,
                    'specifications'=>@$item->product_variant_id ? @$item->productVariant->specifications : [],
                ];
            }),
            'status' => 200,
        ];
    }

}

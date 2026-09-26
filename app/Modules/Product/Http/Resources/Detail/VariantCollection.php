<?php

namespace App\Modules\Product\Http\Resources\Detail;


use Illuminate\Http\Resources\Json\ResourceCollection;


class VariantCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $product = $this->resource;

        $variants = $product->variants()
            ->orderBy('final_price')
            ->orderBy('stock')
            ->get();

        return VariantResource::collection($variants);
    }
}

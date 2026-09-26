<?php

namespace App\Modules\Product\Http\Resources\Detail;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Product\Services\ProductService;

class VariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'product_id'        => $this->product_id,
            'price'             => $this->price,
            'weight'            => $this->weight,
            'discounted_price'  => $this->discounted_price,
            'final_price'       => $this->final_price,
            'stock'             => $this->stock,
            'price_affective'   => $this->price_affective,
            'color_code'        => $this->color_code,
            'specification_ids' => $this->specifications->pluck('id')->toArray(),
            'specifications'    => SpecificationValueResource::collection($this->specifications),
            'images'            => ProductService::getProductImagesSizeSeperated($this->images),
        ];
    }
}

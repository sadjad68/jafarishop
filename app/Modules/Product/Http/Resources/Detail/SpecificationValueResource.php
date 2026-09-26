<?php

namespace App\Modules\Product\Http\Resources\Detail;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Product\Services\ProductService;


class SpecificationValueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'specification_value_id' => $this->id,
            'main_specification_id'  => $this->parent?->id,
        ];
    }
}

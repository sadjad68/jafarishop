<?php

namespace App\Modules\Product\Http\Resources\Detail;


use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'color_code'  => ($this->color_code == null || $this->color_code == 'undefined')
                ? '#000000'
                : $this->color_code,
            'variants'    => VariantResource::collection($this->variants ?? []),
        ];
    }
}

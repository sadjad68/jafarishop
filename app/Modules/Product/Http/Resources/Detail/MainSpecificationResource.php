<?php

namespace App\Modules\Product\Http\Resources\Detail;


use Illuminate\Http\Resources\Json\JsonResource;

class MainSpecificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'main_id'       => $this->id,
            'main_title'    => $this->title,
            'main_is_color' => $this->is_color,
            'children'      => SpecificationResource::collection($this->children ?? []),
        ];
    }
}

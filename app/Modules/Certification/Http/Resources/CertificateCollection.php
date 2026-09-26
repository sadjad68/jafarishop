<?php

namespace App\Modules\Certification\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CertificateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Certification\Entities\Certificate
     *
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'image' => $item->getImage(),
            ];
        });
    }
}

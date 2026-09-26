<?php

namespace App\Modules\Setting\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FooterSocialCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\App\Modules\Service\Entities\Service
     */
    public function toArray($request)
    {

        return [
            'data'=>$this->collection->map(function ($item){
                return[
                    'id'=>@$item->id,
                    'icon'=>@$item->icon,
                    'link'=>@$item->link,

                ];
            }),
            'status'=>200,
        ];
    }
}

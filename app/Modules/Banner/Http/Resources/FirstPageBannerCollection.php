<?php

namespace App\Modules\Banner\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FirstPageBannerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\App\Modules\Banner\Entities\Banner
     */
    public function toArray($request)
    {

        return [
            'data'=>$this->collection->map(function ($item){
                return[
                    'id'=>@$item->id,
                    'image'=>@$item->item_image,
                    'link'=>@$item->link,
                    'place'=>@$item->place,
                    'type'=>@$item->type,
                    'video_link'=>@$item->video_link,
                    'video_cover'=>@$item->video_cover,
                    'title'=>@$item->title,
                    'description'=>@$item->description,
                    'first_button'=>@$item->first_button,


                ];
            }),
            'status'=>200,
        ];
    }
}

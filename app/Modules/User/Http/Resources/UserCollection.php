<?php

namespace App\Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\App\Modules\User\Entities\User
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id'=>$item->id,
                'full_name'=>$item->full_name,
                'mobile'=>$item->mobile,
                'email'=>$item->email,
                'avatar'=>$item->getAvatar(),
                'services'=>$item->servicesCollection,
            ];
        });
    }
}

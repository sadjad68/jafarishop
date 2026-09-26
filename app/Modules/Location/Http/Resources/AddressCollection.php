<?php

namespace App\Modules\Location\Http\Resources;

use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class AddressCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function __construct($collection)
    {
        parent::__construct($collection);


    }
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function ($item){
                return[
                    'success' => true,
                    'id' => $item['id'],
                    'address' => $item['address'],
                    'state_name' => @$item->state->name,
                    'city_name' => @$item->city->name,

                ];
            }),
            'status'=>200,
        ];
    }
}

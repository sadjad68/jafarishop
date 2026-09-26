<?php

namespace App\Modules\Contact\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return $this->transformItemToArray($item);
        })->toArray();
    }
    public function transformItemToArray($item)
    {
        $data = [
            'id' => $item->id,
            'name' => $item->name,
            'mobile' => $item->mobile,
            'title' => $item->title,
            'message' => $item->message,
            'status' => $item->status,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];


        return $data;
    }

}



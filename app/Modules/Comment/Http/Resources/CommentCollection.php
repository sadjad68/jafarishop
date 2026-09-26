<?php

namespace App\Modules\Comment\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\App\Modules\Comment\Entities\Comment
     *
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => @$item->id,
                'name'=>$item->name,
                'user_id'=>$item->user_id,
                'email'=>$item->mobile,
                'content'=>$item->content,
                'commentable_id'=>$item->commentable_id,
                'commentable_type'=>$item->commentable_type,
                'reply_id'=>$item->reply_id,
                'status'=>$item->status,
                'rate'=>$item->rate,
                'replies'=>$item->replies

            ];
        });
    }
}

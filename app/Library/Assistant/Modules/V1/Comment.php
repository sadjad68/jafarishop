<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Comment
{
    public static function create($request): Collection
    {
        return API::post('v1/post-comment', $request);
    }

}

<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Contact
{
    public static function create($request): Collection
    {
        return API::post('v1/post-contact', $request);
    }

}

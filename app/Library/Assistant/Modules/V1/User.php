<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class User
{
    public static function users(): Collection
    {
        return API::get('v1/users');
    }

    public static function get(int $id): Collection
    {
        return API::get('v1/users/' . $id);
    }
}

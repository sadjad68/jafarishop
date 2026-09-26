<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Tag
{
    public static function getList(): Collection
    {
        return API::get('v1/tags');
    }

    public static function getDetail($url, $query): Collection
    {
        return API::get('v1/tag/' . $url, $query);
    }
}

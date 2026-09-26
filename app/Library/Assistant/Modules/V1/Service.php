<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Service
{
    public static function getServiceList(): Collection
    {
        return API::get('v1/services');
    }

    public static function getServiceDetail($url): Collection
    {
        return API::get('v1/service/' . $url);
    }
}

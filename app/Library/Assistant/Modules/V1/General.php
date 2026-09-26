<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class General
{
    public static function firstPage(): Collection
    {
        return API::get('v1/first-page');
    }

    public static function layoutData(): Collection
    {
        return API::get('v1/setting');
    }
    public static function search($request): Collection
    {
        return API::get('v1/search-api', $request);
    }
}

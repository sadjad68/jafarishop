<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Package
{
    public static function getPackageList(): Collection
    {
        return API::get('v1/packages');
    }

    public static function getPackageDetail($url): Collection
    {
        return API::get('v1/package/' . $url);
    }
}

<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Gallery
{
    public static function getCategory(): Collection
    {
        return API::get('v1/galleries');
    }
    public static function getList($url): Collection
    {
        return API::get('v1/gallery/' . $url);
    }
}

<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Seo
{

    public static function getStatic($url): Collection
    {
        return API::get('v1/seo/' . trim($url, '/'));
    }
    public static function getRedirect(): Collection
    {
        return API::get('v1/redirect/');
    }
    public static function getCanonical(): Collection
    {
        return API::get('v1/canonical/');
    }
}

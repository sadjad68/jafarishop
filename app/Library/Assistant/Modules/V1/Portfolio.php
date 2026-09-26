<?php

namespace App\Library\Assistant\Modules\V1;

use App\Library\Assistant\Core\API;
use Illuminate\Support\Collection;

class Portfolio
{
    public static function getList(): Collection
    {
        return API::get('v1/portfolios');
    }
    public static function getServiceForFilter(): Collection
    {
        return API::get('v1/portfolio-filters');
    }
    public static function getListForVue(): Collection
    {
        return API::get('v1/portfolio-vue');
    }

    public static function getDetail($url): Collection
    {
        return API::get('v1/portfolio/' . $url);
    }
}

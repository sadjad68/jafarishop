<?php

namespace App\Modules\Location\Services;

use Illuminate\Support\Facades\Auth;
use App\Modules\Location\Entities\Address;
use App\Modules\Location\Entities\City;
use App\Modules\Location\Entities\State;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Entities\BasketItem;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;


class CityService
{

    public static function findOne($state_id)
    {
      return City::orderBy('name', 'ASC')->where('state_id', $state_id)->get();
    }

}

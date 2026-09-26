<?php

namespace App\Modules\Order\Services;

use App\Library\SiteHelper;
use Illuminate\Support\Arr;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Library\SnappPay;
use App\Modules\Product\DTO\ProductDTO;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;


class SnappPayService
{
    public static function checkSnappay($price)
    {
        $site = SiteHelper::getInformation();
        $bank = Bank::orderBy('id','DESC')->where('bank_type','snappay')->first();
        $snapp =  new SnappPay($bank, $site['site_name']);
        $snapp_data = [
            'snapp_show' => false,
            'snapp_title_message' => null,
            'snapp_description' => null,
        ];
        $response = $snapp->eligible($price);
        if ($response && @$response['response']['eligible'] == true ) {
            $snapp_data = [
                'snapp_show' => true,
                'snapp_title_message' => $response['response']['title_message'],
                'snapp_description' => $response['response']['description'],
            ];
        }
        return $snapp_data;
    }
}

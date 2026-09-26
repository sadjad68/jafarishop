<?php

namespace App\Modules\Order\Library;

use App\Modules\Order\Entities\Bank;

class PaymentGatewayFactory
{
    public static function create(Bank $bank, string $site_name): PaymentGateway
    {
        return match ($bank->bank_type) {
            'zarinPal' => new ZarinPal($bank, $site_name),
            'sep' => new SepSaman($bank, $site_name),
            'sadad' => new Sadad($bank, $site_name),
            'snappay' => new SnappPay($bank, $site_name),
            'saderat' => new Saderat($bank, $site_name),
            'irandargah' => new IranDargah($bank, $site_name),
            'parsian' => new Parsian($bank, $site_name),
            'zibal' => new Zibal($bank, $site_name),
            'aqayepardakht' => new AqayePardakht($bank, $site_name),
            'digipay' => new DigiPay($bank, $site_name),
            default => throw new \InvalidArgumentException("Unsupported bank type: {$bank->bank_type}")
        };
    }
}

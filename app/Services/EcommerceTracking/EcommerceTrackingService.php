<?php

namespace App\Services\EcommerceTracking;

use App\Modules\Setting\Services\SettingService;

class EcommerceTrackingService
{
    public static function isEnabled(): bool
    {
        $settings = SettingService::getFormatSettings(['ecommerce_tracking_enabled']);

        return (int) ($settings['ecommerce_tracking_enabled'] ?? 0) === 1;
    }
}

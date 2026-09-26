<?php

namespace App\Modules\Product\Services;

use Illuminate\Support\Facades\Log;
use App\Modules\General\Helper\Sms;
use App\Modules\Product\Entities\ProductNotification;
use App\Modules\Setting\Entities\Setting;

class NotificationService
{
    protected Sms $smsHelper;

    public function __construct()
    {
        $this->smsHelper = new Sms();
    }

    // =====================================================
    // PUBLIC
    // =====================================================

    public function sendBulkAvailabilityArray(array $ids, ?string $template_url = null): int
    {
        return $this->sendBulkArray($ids, ProductNotification::TYPE_AVAILABILITY, $template_url ?? $this->resolveTemplateUrl());
    }

    public function sendBulkSaleArray(array $ids, ?string $template_url = null): int
    {
        return $this->sendBulkArray($ids, ProductNotification::TYPE_DISCOUNT, $template_url ?? $this->resolveTemplateUrl());
    }

    public function sendBulkAvailability(array $ids): int
    {
        return $this->sendBulkSingle($ids, ProductNotification::TYPE_AVAILABILITY);
    }

    public function sendBulkSale(array $ids): int
    {
        return $this->sendBulkSingle($ids, ProductNotification::TYPE_DISCOUNT);
    }
    // =====================================================
    // METHODS
    // =====================================================

    protected function sendBulkArray(array $ids, string $type, ?string $template_url = null): int
    {
        $template_url = $template_url ?? $this->resolveTemplateUrl();
        Log::info('SERVICE HIT', [
            'ids' => $ids,
            'type' => $type
        ]);
        $notifications = $this->getValidNotifications($ids, $type);
        if ($notifications->isEmpty()) return 0;

        $messages = [];
        $objects = [];

        foreach ($notifications as $notif) {
            $messages[] = [
                'receptor' => $notif->user->mobile,
                'message' => $this->buildMessage($notif,$template_url),
            ];
            $objects[] = $notif;
        }

        $sentCount = 0;

        $chunks = array_chunk($messages, 100);
        $objectChunks = array_chunk($objects, 100);

        foreach ($chunks as $index => $chunk) {

            $result = $this->smsHelper->sendSmsArray($chunk);

            if (!empty($result['success'])) {

                foreach ($objectChunks[$index] as $notif) {
                    $this->markAsSent($notif);
                    $sentCount++;
                }

            } else {

                Log::error("BATCH_FAILED", [
                    'type' => $type,
                    'error' => $result['message'] ?? 'unknown'
                ]);

            }
        }

        return $sentCount;
    }


    protected function sendBulkSingle(array $ids, string $type): int
    {
        $notifications = $this->getValidNotifications($ids, $type);

        $sent = 0;
        $failed = 0;

        foreach ($notifications as $notif) {

            $message = $this->buildMessage($notif, $this->resolveTemplateUrl());

            $result = $this->smsHelper->sendSms($message, $notif->user->mobile);

            if (!empty($result['success'])) {

                $this->markAsSent($notif);
                $sent++;

            } else {

                $failed++;

                Log::error("SMS_SEND_FAILED", [
                    'type' => $type,
                    'mobile' => $notif->user->mobile,
                    'notif_id' => $notif->id,
                    'error' => $result['message'] ?? 'unknown'
                ]);
            }
        }

        Log::info("SMS Single Batch Completed", [
            'type' => $type,
            'sent' => $sent,
            'failed' => $failed
        ]);

        return $sent;
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function getValidNotifications(array $ids, string $type)
    {

        $me = ProductNotification::whereIn('id', $ids)->count();


        return ProductNotification::with(['user', 'product', 'variant'])
            ->whereIn('id', $ids)
            ->where('type', $type)
            ->where('is_sent', false)
            ->get()
            ->filter(function ($n) use ($type) {

                if (!$n->user || !$n->user->mobile || !$n->product) {
                    return false;
                }

                $variant = $n->variant;
                $product = $n->product;

                // -----------------------
                // AVAILABILITY
                // -----------------------
                if ($type === ProductNotification::TYPE_AVAILABILITY) {

                    $stock = $variant?->stock ?? $product->stock ?? 0;

                    return intval($stock) > 0;
                }

                // -----------------------
                // DISCOUNT
                // -----------------------
                if ($type === ProductNotification::TYPE_DISCOUNT) {

                    $price = $variant?->discounted_price ?? $product->discounted_price ?? 0;

                    return intval($price) > 0;
                }

                return false;
            });
    }

    protected function buildMessage(ProductNotification $notif, ?string $template_url = null): string
    {
        $product = $notif->product;

        $template_url = $template_url ?? $this->resolveTemplateUrl();
        $baseUrl = $template_url !== '' ? 'https://' . $template_url : '';

        $productPath = parse_url(\App\Library\SiteUrl::product($product), PHP_URL_PATH);
        $productFullUrl = rtrim($baseUrl, '/') . $productPath;

        $site_name = cache()->remember('setting_siteName_fa', 86400, function () {
            return \App\Modules\Setting\Entities\Setting::where('key', 'siteName_fa')->value('value') ?? 'فروشگاه';
        });

        if ($notif->type === ProductNotification::TYPE_AVAILABILITY) {
            return "کاربر گرامی، محصول «{$product->title}» هم اکنون موجود شد." . "\r\n" .
                "لینک محصول: " . "\r\n" .
                $productFullUrl . "\r\n" .
                $site_name;
        }

        if ($notif->type === ProductNotification::TYPE_DISCOUNT) {
            $priceFormatted = number_format($product->discounted_price);
            return "مژده! محصول «{$product->title}» حراج شد." . "\r\n" .
                "قیمت جدید: {$priceFormatted} تومان" . "\r\n" .
                "لینک محصول: " . "\r\n" .
                $productFullUrl . "\r\n" .
                $site_name;
        }

        return '';
    }

    protected function markAsSent(ProductNotification $notif): void
    {
        $notif->update([
            'is_sent' => true,
            'sent_at' => now()
        ]);
    }

    protected function resolveTemplateUrl(): string
    {
        $url = config('setting.template_url');
        if ($url) {
            return str_replace(['https://', 'http://'], '', $url);
        }

        return \App\Library\SiteHelper::templateUrl();
    }
}
// TIP for developer - here you can search sending sms problem in logs like :
//grep "SMS_SEND_FAILED" storage/logs/laravel.log
//grep "SMS_SEND_FAILED" storage/logs/laravel.log | grep "availability_batch"
//grep "SMS_SEND_FAILED" storage/logs/laravel.log | grep "sale_batch"

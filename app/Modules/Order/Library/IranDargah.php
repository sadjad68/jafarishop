<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Jobs\ZarinpalInquiryJob;
use Illuminate\Support\Facades\Log;

class IranDargah implements PaymentGateway
{
    private $merchant;
    private $site_name;

    private const EP_REQUEST = 'https://dargaah.ir/payment';
    private const EP_VERIFY  = 'https://dargaah.ir/verification';
    private const EP_REDIRECT = 'https://dargaah.ir/ird/startpay/';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.irandargah';

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->merchant = $config['MerchantId'] ?? 'TEST';
        $this->site_name = $site_name;
    }

    /*
     |--------------------------------------------------------------------------
     | 1) ایجاد تراکنش و گرفتن authority
     |--------------------------------------------------------------------------
     */
    public function getBankToken($amount, $order): PaymentPostDTO
    {
        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);
        $amount = GatewayAmountHelper::tomanToRial($paymentToman);

        $payload = [
            "merchantID"   => $this->merchant,
            "amount"       => $amount,
            "callbackURL"  => route(self::CALLBACK_ROUTE_NAME),
            "orderId"      => $order->id,
            "cardNumber"   => null,
        ];

        $resp = $this->sendJsonRequest(self::EP_REQUEST, $payload);

        Log::info('IranDargah getBankToken payload', $payload);
        Log::info('IranDargah getBankToken response', [json_encode($resp)]);

        if (!$resp['ok']) {
            return PaymentPostDTO::fromData('failed');
        }

        $result = $resp['json'];

        if ((int)$result['status'] === 200) {
            return PaymentPostDTO::fromData('success', [
                'Authority' => $result['authority']
            ]);
        }

        return PaymentPostDTO::fromData('failed');
    }

    /*
     |--------------------------------------------------------------------------
     | 2) تأیید تراکنش
     |--------------------------------------------------------------------------
     */
    public function verifyTransaction($callbackData, $price, $order = null): PaymentVerifyDTO
    {

        if (!isset($callbackData['authority'])) {
            return PaymentVerifyDTO::fromData('failed');
        }
        $paymentToman = $order
            ? GatewayAmountHelper::resolvePaymentToman((int) $price, $order)
            : (int) $price;
        $price = GatewayAmountHelper::tomanToRial($paymentToman);

        $payload = [
            "merchantID" => $this->merchant,
            "authority"  => $callbackData['authority'],
            "amount"     => intval($price),
            "orderId"    => $order?->id ?? null
        ];

        $resp = $this->sendJsonRequest(self::EP_VERIFY, $payload);

        if (!$resp['ok']) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $result = $resp['json'];

        if ((int)$result['status'] === 100) {
            return PaymentVerifyDTO::fromData('success', [
                'RefID' => $result['refId']
            ]);
        }

        return PaymentVerifyDTO::fromData('failed');
    }

    /*
     |--------------------------------------------------------------------------
     | 3) هدایت به صفحه پرداخت
     |--------------------------------------------------------------------------
     */
    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        $authority = $postData->getPostData()['Authority'];
        header('Location: ' . self::EP_REDIRECT . $authority);
        exit;
    }

    /*
     |--------------------------------------------------------------------------
     | ارسال درخواست JSON با Http facade
     |--------------------------------------------------------------------------
     */
    private function sendJsonRequest(string $url, array $payload): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])
                ->timeout(30)
                ->withOptions([
                    'verify' => false // معادل CURLOPT_SSL_VERIFYPEER = 0
                ])
                ->post($url, $payload);
            if ($response->failed()) {
                return ['ok' => false, 'json' => null];
            }

            return ['ok' => true, 'json' => $response->json()];

        } catch (\Throwable $e) {
            \Log::error("DARGAAH ERROR: " . $e->getMessage());
            return ['ok' => false, 'json' => null];
        }
    }
}

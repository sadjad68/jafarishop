<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Jobs\ZarinpalInquiryJob;
use App\Modules\Order\Services\ZarinpalInquiryService;

class ZarinPal implements PaymentGateway
{
    private $merchant;
    private $site_name;
    private const EP_REQUEST = 'https://payment.zarinpal.com/pg/v4/payment/request.json';
    private const EP_VERIFY = 'https://payment.zarinpal.com/pg/v4/payment/verify.json';
    private const EP_INQUIRY = 'https://payment.zarinpal.com/pg/v4/payment/inquiry.json';
    private const EP_REDIRECT = 'https://payment.zarinpal.com/pg/StartPay/';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.zarin-pal';
    private const USER_AGENT = 'ZarinPal Rest Api v1';

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->merchant = $config['MerchantId'] ?? null;
        $this->site_name = $site_name;
    }

    public function getBankToken($amount, $order): PaymentPostDTO
    {
        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);
        $amount = GatewayAmountHelper::tomanToRial($paymentToman);
        $description = $this->site_name . ' خرید از ';

        $payload = [
            'merchant_id' => $this->merchant,
            'amount' => $amount,
            'callback_url' => route(self::CALLBACK_ROUTE_NAME),
            'description' => $description,
            'metadata' => []
        ];

        $resp = $this->sendJsonRequest(self::EP_REQUEST, $payload, 'POST');
        if (!$resp['ok']) {
            return PaymentPostDTO::fromData('failed');
        }

        $result = $resp['json'];
        if (isset($result['data']['code']) && (int)$result['data']['code'] === 100) {
            return PaymentPostDTO::fromData('success', ['Authority' => $result['data']['authority'] ?? null]);
        }

        return PaymentPostDTO::fromData('failed');
    }

    public function verifyTransaction($callbackData, $price, $order = null): PaymentVerifyDTO
    {
        $paymentToman = $order
            ? GatewayAmountHelper::resolvePaymentToman((int) $price, $order)
            : (int) $price;
        $amount = GatewayAmountHelper::tomanToRial($paymentToman);
        \Log::info('54');
        \Log::info($callbackData);
        if ($callbackData['Status'] !== "OK") {
            return PaymentVerifyDTO::fromData('failed');
        }

        $payload = [
            'merchant_id' => $this->merchant,
            'authority' => $callbackData['Authority'],
            'amount' => $amount,
        ];

        $resp = $this->sendJsonRequest(self::EP_VERIFY, $payload, 'POST');
        \Log::info('67');
        \Log::info($resp);
        if (!$resp['ok']) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $result = $resp['json'];

        if (isset($result['data']['code']) && ((int)$result['data']['code'] === 100 || (int)$result['data']['code'] === 101)) {
            return PaymentVerifyDTO::fromData(
                'success',
                ['RefID' => $result['data']['ref_id'] ?? null,]
            );
        }

        return PaymentVerifyDTO::fromData('failed');
    }

    public function inquiry($authority)
    {
        $payload = [
            'merchant_id' => $this->merchant,
            'authority' => $authority,
        ];

        $resp = $this->sendJsonRequest(self::EP_INQUIRY, $payload, 'POST');
        if (!$resp['ok']) {
            Log::warning('ZarinPal inquiry HTTP failed', [
                'authority' => $authority,
            ]);

            return [
                'ok' => false,
                'status' => null,
                'code' => null,
                'errors' => is_array($resp['json']) ? ($resp['json']['errors'] ?? null) : null,
            ];
        }

        $result = $resp['json'];
        $status = isset($result['data']['status'])
            ? strtoupper((string) $result['data']['status'])
            : null;

        return [
            'ok' => true,
            'status' => $status !== '' ? $status : null,
            'code' => isset($result['data']['code']) ? (int) $result['data']['code'] : null,
            'errors' => $result['errors'] ?? null,
        ];
    }

    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        if (config('queue.default') !== 'sync') {
            ZarinpalInquiryJob::dispatch($order->id, $this->site_name)
                ->delay(now()->addMinutes(ZarinpalInquiryService::INQUIRY_DELAY_MINUTES));
        }

        $authority = $postData->getPostData()['Authority'];
        header('Location: ' . self::EP_REDIRECT . $authority);
        exit;
    }

    private function sendJsonRequest(string $url, array $payload = [], string $method = 'POST'): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => self::USER_AGENT,
            ])
                ->timeout(30)
                ->send($method, $url, ['json' => $payload]);

            if ($response->failed()) {
                return ['ok' => false, 'json' => null];
            }

            $json = $response->json();

            if (!is_array($json)) {
                return ['ok' => false, 'json' => null];
            }

            return ['ok' => true, 'json' => $json];
        } catch (\Throwable $e) {
            return ['ok' => false, 'json' => null];
        }
    }
}

<?php

namespace App\Modules\Order\Library;

use Exception;
use Illuminate\Support\Facades\Http;
use App\Modules\Order\Entities\Order;

class Zibal implements PaymentGateway
{
    private $merchant;
    private $site_name;
    private const EP_REQUEST  = 'https://gateway.zibal.ir/v1/request';
    private const EP_VERIFY   = 'https://gateway.zibal.ir/v1/verify';
    private const EP_REDIRECT = 'https://gateway.zibal.ir/start/';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.zibal-bank';
    private const USER_AGENT  = 'Zibal Rest Api v1';

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->merchant = $config['MerchantId'] ?? null;
        $this->site_name = $site_name;
    }

    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);
        $amount = GatewayAmountHelper::tomanToRial($paymentToman);
        $orderId = $order->id;
        $callbackUrl = route(self::CALLBACK_ROUTE_NAME);
        $description = 'خرید از ' . $this->site_name;

        $data = [
            'merchant'    => $this->merchant,
            'amount'      => $amount,
            'callbackUrl' => $callbackUrl,
            'description' => $description,
            'orderId'     => $orderId,
        ];

        $result = self::CallAPI(self::EP_REQUEST, $data);

        if (!$result || !isset($result['result']) || $result['result'] != 100) {
            \Log::info('Zibal Request Failed');
            \Log::info($result);
            return PaymentPostDTO::fromData('failed');
        }

        return PaymentPostDTO::fromData('success', ['trackId' => $result['trackId']]);
    }

    public function verifyTransaction($callbackData, $price = null, Order $order = null): PaymentVerifyDTO
    {
        $trackId = $callbackData['trackId'] ?? null;

        if (!$trackId) {
            \Log::info('Zibal: trackId not found in callback');
            return PaymentVerifyDTO::fromData('failed');
        }

        $verifyData = [
            'merchant' => $this->merchant,
            'trackId'  => $trackId,
        ];

        $result = self::CallAPI(self::EP_VERIFY, $verifyData);

        \Log::info('Zibal Verify Response');
        \Log::info($result);

        if ($result && isset($result['result']) && ($result['result'] == 100 || $result['result'] == 201)) {
            return PaymentVerifyDTO::fromData('success',
                [
                    'trackId'    => $result['trackId'] ?? null,
                    'refNumber'  => $result['refNumber'] ?? null,
                    'cardNumber' => $result['cardNumber'] ?? null,
                ]
            );
        }

        return PaymentVerifyDTO::fromData('failed');
    }

    public static function CallAPI($url, $data = false)
    {
        try {
            $request = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent'   => self::USER_AGENT,
            ])->withOptions([
                'verify' => false,
            ])->timeout(30);

            $response = $request->send('POST', $url, [
                'json' => $data ?: new \stdClass(),
            ]);

            $body = $response->body();
            return !empty($body) ? json_decode($body, true) : false;
        } catch (Exception $ex) {
            \Log::error('Zibal API Exception: ' . $ex->getMessage());
            return false;
        }
    }

    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        $trackId = $postData->getPostData()['trackId'];
        $url = self::EP_REDIRECT . $trackId;

        header('Location: ' . $url);
        exit;
    }
}

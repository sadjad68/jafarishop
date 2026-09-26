<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use App\Modules\Order\Entities\Order;

class SepSaman implements PaymentGateway
{
    private $TerminalId;
    private $site_name;
    private const EP_TOKEN = 'https://sep.shaparak.ir/onlinepg/onlinepg';
    private const EP_VERIFY = 'https://sep.shaparak.ir/verifyTxnRandomSessionkey/ipg/VerifyTransaction';
    private const EP_REDIRECT_BASE = 'https://sep.shaparak.ir/OnlinePG/SendToken';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.saman-bank';
    private const USER_AGENT = 'SepSaman Rest Api v1';

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->TerminalId = $config['TerminalId'] ?? null;
        $this->site_name = $site_name;
    }

    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);
        $amount = GatewayAmountHelper::tomanToRial($paymentToman);
        $payload = [
            "action" => "token",
            "TerminalId" => $this->TerminalId,
            "Amount" => $amount,
            "ResNum" => $order->id,
            "RedirectUrl" => route(self::CALLBACK_ROUTE_NAME),
            "CellNumber" => $order->user->mobile,
        ];

        $resp = $this->sendJsonRequest(self::EP_TOKEN, $payload, 'POST');
        \Log::info('38');
        \Log::info($resp);
        if (!$resp['ok']) {
            return PaymentPostDTO::fromData('failed');
        }

        $response = $resp['json'];
        \Log::info($response);
        if (@$response["status"] != 1) {
            return PaymentPostDTO::fromData('failed');
        }
        return PaymentPostDTO::fromData('success', ['token' => $response['token'] ?? null]);
    }

    public function verifyTransaction($callbackData, $price, $order = null): PaymentVerifyDTO
    {
        \Log::info('54');
        \Log::info($callbackData);
        if (@$callbackData['State'] == 'CanceledByUser') {
            return PaymentVerifyDTO::fromData(
                'failed',
                [
                    "RefNum" => $callbackData["RefNum"],
                    "TraceNo" => $callbackData["TraceNo"],
                ]
            );
        }

        $params = [
            'TerminalNumber' => $this->TerminalId,
            'RefNum' => $callbackData['RefNum'],
        ];

        $resp = $this->sendJsonRequest(self::EP_VERIFY, $params, 'POST');
        \Log::info('70');
        \Log::info($resp);
        if ($callbackData['Status'] == 2) {
            return PaymentVerifyDTO::fromData(
                'success',
                [
                    "RefNum" => $callbackData["RefNum"],
                    "TraceNo" => $callbackData["TraceNo"],
                ]
            );
        } else {
            return PaymentVerifyDTO::fromData(
                'failed',
                [
                    "RefNum" => $callbackData["RefNum"],
                    "TraceNo" => $callbackData["TraceNo"],
                ]
            );
        }
    }

    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        $token = $postData->getPostData()['token'];
        $url = self::EP_REDIRECT_BASE . '?token=' . $token;
        header("Location: $url");
        exit;
    }

    /**
     *
     * @param string $url
     * @param array $payload
     * @param string $method
     * @return array{ok:bool,json:?array}
     */
    private function sendJsonRequest(string $url, array $payload = [], string $method = 'POST'): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
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
            // در صورت نیاز: \Log::error('SepSaman HTTP error: '.$e->getMessage(), ['url' => $url, 'payload' => $payload]);
            return ['ok' => false, 'json' => null];
        }
    }
}

<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use App\Modules\Order\Entities\Order;

class Saderat implements PaymentGateway
{
    private string $terminalId;
    private string $site_name;

    private const EP_GET_TOKEN = 'https://sepehr.shaparak.ir/Rest/V1/PeymentApi/GetToken';
    private const EP_ADVICE    = 'https://sepehr.shaparak.ir/Rest/V1/PeymentApi/Advice';
    private const EP_REDIRECT  = 'https://sepehr.shaparak.ir/Payment/Pay';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.saderat';
    private const USER_AGENT = 'Sepehr (Saderat) Rest Api v1';

    /**
     * $bank['config'] JSON should contain:
     *   - TerminalID
     */
    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->terminalId = (string)($config['TerminalID'] ?? '');
        $this->site_name  = $site_name;
    }

    /**
     * Step 1: Get Access Token from Sepehr and return data for redirect step.
     * Returns PaymentPostDTO with ['token' => Accesstoken].
     */
    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        if (empty($this->terminalId)) {
            return PaymentPostDTO::fromData('failed');
        }

        $invoiceId = (string)($order->id ?? uniqid('inv_', true));

        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);
        $amount = GatewayAmountHelper::tomanToRial($paymentToman);

        $payload = [
            'TerminalID'  => $this->terminalId,
            'Amount'      => (string)$amount,
            'InvoiceID'   => $invoiceId,
            'callbackURL' => route(self::CALLBACK_ROUTE_NAME),
            'payload'     => '',
        ];

        $resp = $this->sendJsonRequest(self::EP_GET_TOKEN, $payload, 'POST');
        if (!$resp['ok']) {
            return PaymentPostDTO::fromData('failed');
        }

        $json = $resp['json'];

        // در مستندات نام فیلد ممکن است "Accesstoken" یا "AccessToken" باشد
        $token = $json['Accesstoken'] ?? $json['AccessToken'] ?? null;
        $statusOk = isset($json['Status']) && (string)$json['Status'] === '0';
        if ($statusOk && $token) {
            return PaymentPostDTO::fromData('success', ['token' => $token,'invoiceid'=>$invoiceId]);
        }
        return PaymentPostDTO::fromData('failed');
    }

    /**
     * Step 2: Verify
     * Sepehr sends you back to callback with fields like respcode, digitalreceipt, amount, rrn, ...
     * If respcode==0 => call Advice with digitalreceipt + Tid(terminal id).
     */
    public function verifyTransaction(array $callbackData, int $price, Order $order): PaymentVerifyDTO
    {

        $respCode = (int)($callbackData['respcode'] ?? $callbackData['RespCode'] ?? -1);
        if ($respCode !== 0) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $digitalReceipt = $callbackData['digitalreceipt'] ?? $callbackData['DigitalReceipt'] ?? null;
        if (!$digitalReceipt) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $payload = [
            'digitalreceipt' => (string)$digitalReceipt,
            'Tid'            => $this->terminalId,
        ];

        $resp = $this->sendJsonRequest(self::EP_ADVICE, $payload, 'POST');

        if (!$resp['ok']) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $json = $resp['json'];
        // Status: Ok / NOK / Duplicate
        $status = strtoupper((string)($json['Status'] ?? ''));
        $returnId = (string)($json['ReturnId'] ?? '');
        $rial_price = GatewayAmountHelper::tomanToRial(
            GatewayAmountHelper::resolvePaymentToman((int) $price, $order)
        );
        $amountMatches = ($returnId !== '') && ((int)$returnId === (int)$rial_price);
        if (in_array($status, ['OK', 'DUPLICATE'], true) && $amountMatches) {
            // بهترین شناسه برای ذخیره: rrn (شماره سند بانکی)؛ اگر نبود، digitalreceipt
            $ref = $callbackData['rrn'] ?? $callbackData['RRN'] ?? $digitalReceipt;
            return PaymentVerifyDTO::fromData('success', ['RefID' => $ref]);
        }

        return PaymentVerifyDTO::fromData('failed');
    }

    /**
     * Step 3: Redirect user to Sepehr payment page
     */
    public function redirect(PaymentPostDTO $postData, Order $order)
    {
        $data = $postData->getPostData();
        $token = $data['token'] ?? null;
        if (!$token) {
            abort(400, 'Payment token missing.');
        }

        $url = self::EP_REDIRECT . '?token=' . urlencode($token) . '&terminalId=' . urlencode($this->terminalId);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Reuse the same HTTP helper pattern as in ZarinPal.
     */
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

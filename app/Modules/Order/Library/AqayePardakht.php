<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use App\Modules\Order\Entities\Order;

class AqayePardakht implements PaymentGateway
{
    private const EP_CREATE = 'https://panel.aqayepardakht.ir/api/v2/create';
    private const EP_VERIFY = 'https://panel.aqayepardakht.ir/api/v2/verify';
    private const EP_REDIRECT = 'https://panel.aqayepardakht.ir/startpay/';
    /** @see https://aqayepardakht.ir/api/ — with pin "sandbox" use this startpay path */
    private const EP_REDIRECT_SANDBOX = 'https://panel.aqayepardakht.ir/startpay/sandbox/';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.aqayepardakht';

    private ?string $pin;

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->pin = $config['pin'] ?? null;
    }

    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        if (empty($this->pin)) {
            return PaymentPostDTO::fromData('failed');
        }

        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);

        $payload = [
            'pin' => $this->pin,
            'amount' => $paymentToman,
            'callback' => route(self::CALLBACK_ROUTE_NAME),
            'callback_method' => 'GET',
            'invoice_id' => (string) $order->id,
            'description' => 'سفارش #' . $order->id,
        ];
        // Optional: do not send fake card_number — it limits payment to that card only and breaks verify
        // when the customer uses another card (per https://aqayepardakht.ir/api/ ).
        if ($user = $order->user) {
            $mobile = preg_replace('/\D+/', '', (string) ($user->mobile ?? ''));
            if (strlen($mobile) >= 10) {
                $payload['mobile'] = $mobile;
            }
            $email = (string) ($user->email ?? '');
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $payload['email'] = $email;
            }
        }
        $resp = $this->sendJsonRequest(self::EP_CREATE, $payload);
        if (!$resp['ok']) {
            return PaymentPostDTO::fromData('failed');
        }

        $result = $resp['json'];
        if (($result['status'] ?? null) === 'success' && !empty($result['transid'])) {
            return PaymentPostDTO::fromData('success', [
                'transid' => $result['transid'],
                // must match verify; prefer this over re-reading order totals at callback
                'amount' => $paymentToman,
            ]);
        }

        \Log::warning('AqayePardakht create failed', ['response' => $result]);
        return PaymentPostDTO::fromData('failed');
    }

    public function verifyTransaction(array $callbackData, int $price, Order $order): PaymentVerifyDTO
    {
        $transid = $callbackData['transid'] ?? null;
        $status = (int)($callbackData['status'] ?? 0);

        if (empty($transid) || $status !== 1 || empty($this->pin)) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $tx = $order->transaction_info;
        $orderTx = is_array($tx) ? $tx : (json_decode((string) $tx, true) ?: []);
        $storedAmount = (int) ($orderTx['post']['amount'] ?? 0);
        $verifyAmount = $storedAmount > 0
            ? $storedAmount
            : GatewayAmountHelper::resolvePaymentToman((int) $price, $order);

        $payload = [
            'pin' => $this->pin,
            'amount' => (int) $verifyAmount,
            'transid' => $transid,
        ];

        $resp = $this->sendJsonRequest(self::EP_VERIFY, $payload);
        if (!$resp['ok']) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $result = $resp['json'];
        $code = (int) ($result['code'] ?? 0);
        $apiStatus = $result['status'] ?? null;
        // Doc: code 2 = already verified+paid (listed under "error" but should complete the order)
        if ($code === 2) {
            return PaymentVerifyDTO::fromData('success', [
                'transid' => $transid,
                'code' => $code,
                'tracking_number' => $callbackData['tracking_number'] ?? null,
                'cardnumber' => $callbackData['cardnumber'] ?? null,
                'bank' => $callbackData['bank'] ?? null,
            ]);
        }
        if ($apiStatus === 'success' && $code === 1) {
            return PaymentVerifyDTO::fromData('success', [
                'transid' => $transid,
                'code' => $code,
                'tracking_number' => $callbackData['tracking_number'] ?? null,
                'cardnumber' => $callbackData['cardnumber'] ?? null,
                'bank' => $callbackData['bank'] ?? null,
            ]);
        }

        \Log::warning('AqayePardakht verify failed', [
            'response' => $result,
            'code_meaning' => self::verifyErrorMessage($result),
            'amount_sent' => $verifyAmount,
            'transid' => $transid,
        ]);
        return PaymentVerifyDTO::fromData('failed');
    }

    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        $transid = $postData->getPostData()['transid'] ?? null;
        if (empty($transid)) {
            abort(400, 'Invalid transaction id');
        }

        $startPay = $this->isSandboxPin() ? self::EP_REDIRECT_SANDBOX : self::EP_REDIRECT;
        header('Location: ' . $startPay . $transid);
        exit;
    }

    private function isSandboxPin(): bool
    {
        return $this->pin !== null && strcasecmp(trim($this->pin), 'sandbox') === 0;
    }

    /**
     * Human-readable messages for verify API (aligned with common gateway mappings).
     *
     * @param  array<string, mixed>  $result
     */
    private static function verifyErrorMessage(array $result): string
    {
        $raw = $result['code'] ?? $result['message'] ?? null;
        if (isset($result['message']) && is_string($result['message']) && $result['message'] !== '') {
            return $result['message'];
        }
        if ($raw === null || $raw === '') {
            return 'unknown';
        }
        $code = (int) $raw;

        return match ($code) {
            -1 => 'amount_empty',
            -2 => 'pin_empty',
            -3 => 'callback_empty',
            -4 => 'amount_invalid',
            -5 => 'amount_out_of_range',
            -6 => 'pin_wrong',
            -7 => 'transid_empty',
            -8 => 'transaction_not_found',
            -9 => 'pin_mismatch',
            -10 => 'amount_mismatch',
            -11 => 'gateway_inactive_or_pending',
            -12 => 'merchant_request_not_allowed',
            -13 => 'card_format_invalid',
            0 => 'payment_not_completed',
            2 => 'already_verified_duplicate_request',
            default => 'code_' . $code,
        };
    }

    private function sendJsonRequest(string $url, array $payload): array
    {
        try {
            $forLog = $payload;
            if (isset($forLog['pin'])) {
                $forLog['pin'] = '***';
            }
            \Log::info('sendJsonRequest', [
                'url' => $url,
                'payload' => $forLog,
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->timeout(30)->post($url, $payload);

            \Log::info('sendJsonRequest');
            \Log::info($response);
            \Log::info('sendJsonRequest');
            \Log::info('sendJsonRequest response detail', [
                'url' => $url,
                'http_status' => $response->status(),
                'body' => $response->body(),
            ]);

            $json = $response->json();
            if (!is_array($json)) {
                \Log::warning('AqayePardakht non-JSON or empty response', [
                    'url' => $url,
                    'http_status' => $response->status(),
                    'body_preview' => \Illuminate\Support\Str::limit($response->body(), 500),
                ]);
                return ['ok' => false, 'json' => null];
            }

            // Many Aqaye Pardakht errors use 4xx with a JSON body; treat parsable JSON as transport OK
            // and let create/verify interpret status, code, and message.
            return ['ok' => true, 'json' => $json];
        } catch (\Throwable $e) {
            \Log::error('AqayePardakht API error: ' . $e->getMessage());
            return ['ok' => false, 'json' => null];
        }
    }
}

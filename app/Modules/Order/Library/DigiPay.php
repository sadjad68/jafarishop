<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Modules\Order\Entities\Order;

class DigiPay implements PaymentGateway
{
    private const CALLBACK_ROUTE_NAME = 'basket.finish.digipay';
    private const TICKET_TYPE = 11;
    private const VERIFY_TYPE = 5;
    /** UPG: BPG (اعتباری) — سرویس تحویل فقط برای ۵ و ۱۳ */
    private const PURCHASE_TYPE_CREDIT_BPG = 5;
    /** UPG: CPG / BNPL — همان type ریفاند/verify */
    private const PURCHASE_TYPE_BNPL_CPG = 13;
    private const PURCHASE_TYPE_WALLET = 11;
    private const DIGIPAY_VERSION = '2022-02-02';
    private const REQUEST_TIMEOUT_SECONDS = 600;

    private const TEST_API_BASE_URL = 'https://uat.mydigipay.info/digipay/api';
    private const LIVE_API_BASE_URL = 'https://api.mydigipay.com/digipay/api';

    private ?string $clientId = null;
    private ?string $clientSecret = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $accessToken = null;
    private ?string $siteName = null;
    private bool $isTest = true;
    private string $preferredGateway = '';

    public function __construct($bank, $siteName)
    {
        $config = is_array($bank['config'] ?? null)
            ? $bank['config']
            : json_decode($bank['config'] ?? '[]', true);
        $this->clientId = $config['client_id'] ?? null;
        $this->clientSecret = $config['client_secret'] ?? null;
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        if (array_key_exists('is_test', $config)) {
            $this->isTest = (int)$config['is_test'] === 1;
        } elseif (array_key_exists('is_live', $config)) {
            $this->isTest = !((int)$config['is_live'] === 1);
        }
        $this->preferredGateway = (string)($config['preferred_gateway'] ?? '');
        $this->siteName = $siteName;

        $this->authorize();
    }

    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        if (!$this->accessToken) {
            return PaymentPostDTO::fromData('failed');
        }

        $order->loadMissing('bank', 'user');
        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);

        $payload = [
            'cellNumber' => $this->normalizeMobile($order->user?->mobile),
            'amount' => GatewayAmountHelper::tomanToRial($paymentToman),
            'providerId' => (string)$order->id,
            'callbackUrl' => route(self::CALLBACK_ROUTE_NAME),
        ];

        if ($this->preferredGateway !== '') {
            $payload['additionalInfo'] = [
                'preferredGateway' => (int)$this->preferredGateway,
            ];
        }

        $response = Http::timeout(self::REQUEST_TIMEOUT_SECONDS)
            ->withHeaders($this->requestHeaders())
            ->withToken($this->accessToken)
            ->post($this->baseUrl() . '/tickets/business?type=' . self::TICKET_TYPE, $payload)
            ->json();

        if (($response['result']['status'] ?? null) !== 0 || empty($response['redirectUrl'])) {
            Log::warning('DigiPay ticket request failed', ['response' => $response]);
            return PaymentPostDTO::fromData('failed');
        }

        return PaymentPostDTO::fromData('success', [
            'providerId' => (string)$order->id,
            'ticket' => $response['ticket'] ?? null,
            'redirectUrl' => $response['redirectUrl'],
        ]);
    }

    public function verifyTransaction(array $callbackData, int $price, Order $order): PaymentVerifyDTO
    {
        if (!$this->accessToken) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $trackingCode = $callbackData['trackingCode'] ?? $callbackData['tracking_code'] ?? null;
        if (!$trackingCode) {
            Log::warning('DigiPay callback missing trackingCode', ['callback' => $callbackData]);
            return PaymentVerifyDTO::fromData('failed');
        }

        $purchaseType = isset($callbackData['type']) ? (int) $callbackData['type'] : self::VERIFY_TYPE;

        $payload = [
            'trackingCode' => $trackingCode,
            'providerId' => (string)($order->id),
        ];

        $response = Http::timeout(self::REQUEST_TIMEOUT_SECONDS)
            ->withHeaders([
                'Content-Type' => 'application/json; charset=UTF-8',
            ])
            ->withToken($this->accessToken)
            ->post($this->baseUrl() . '/purchases/verify?type=' . $purchaseType, $payload)
            ->json();

        if (($response['result']['status'] ?? null) !== 0) {
            Log::warning('DigiPay verify failed', ['response' => $response]);
            return PaymentVerifyDTO::fromData('failed', $response ?? []);
        }

        $verifiedTracking = (string) ($response['trackingCode'] ?? $trackingCode);
        $this->deliverPurchaseIfApplicable($order, $purchaseType, $verifiedTracking);

        return PaymentVerifyDTO::fromData('success', [
            'trackingCode' => $response['trackingCode'] ?? null,
            'providerId' => $response['providerId'] ?? null,
            'fpCode' => $response['fpCode'] ?? null,
            'fpName' => $response['fpName'] ?? null,
            'paymentGateway' => $response['paymentGateway'] ?? null,
            'amount' => $response['amount'] ?? null,
            'digipayPurchaseType' => $purchaseType,
        ]);
    }

    /**
     * UPG §۹ تحویل خرید — فقط BPG (type=5) و CPG/BNPL (type=13). ولت (۱۱) و سایر انواع بدون فراخوانی.
     *
     * @param  array<int, string>  $products
     */
    public function requestDeliver(int $purchaseType, string $trackingCode, string $invoiceNumber, array $products, int $deliveryDateEpochMs): array
    {
        if (!$this->accessToken) {
            Log::warning('DigiPay deliver skipped: no access token');

            return ['success' => false, 'response' => []];
        }
        if ($products === []) {
            Log::warning('DigiPay deliver skipped: empty products', ['trackingCode' => $trackingCode]);

            return ['success' => false, 'response' => []];
        }

        $payload = [
            'deliveryDate' => $deliveryDateEpochMs,
            'invoiceNumber' => $invoiceNumber,
            'trackingCode' => $trackingCode,
            'products' => array_values($products),
        ];

        $httpResponse = Http::timeout(self::REQUEST_TIMEOUT_SECONDS)
            ->withHeaders([
                'Content-Type' => 'application/json; charset=UTF-8',
            ])
            ->withToken($this->accessToken)
            ->post($this->baseUrl() . '/purchases/deliver?type=' . $purchaseType, $payload);

        $response = $httpResponse->json();
        if (!is_array($response)) {
            Log::warning('DigiPay deliver invalid response', ['body' => $httpResponse->body(), 'trackingCode' => $trackingCode]);

            return ['success' => false, 'response' => []];
        }
        if (($response['result']['status'] ?? null) !== 0) {
            Log::warning('DigiPay deliver failed', ['response' => $response, 'trackingCode' => $trackingCode]);

            return ['success' => false, 'response' => $response];
        }

        return ['success' => true, 'response' => $response];
    }

    private function deliverPurchaseIfApplicable(Order $order, int $purchaseType, string $trackingCode): void
    {
        if ($purchaseType === self::PURCHASE_TYPE_WALLET) {
            return;
        }
        if (!in_array($purchaseType, [self::PURCHASE_TYPE_CREDIT_BPG, self::PURCHASE_TYPE_BNPL_CPG], true)) {
            return;
        }

        $order->loadMissing('items');
        $products = [];
        foreach ($order->items as $line) {
            $pid = (string) $line->product_id;
            $products[] = $line->product_variant_id
                ? $pid . '-v' . $line->product_variant_id
                : $pid;
        }
        if ($products === []) {
            $products[] = 'order-' . $order->id;
        }

        $deliveryDateMs = (int) round(microtime(true) * 1000);
        $result = $this->requestDeliver(
            $purchaseType,
            $trackingCode,
            (string) $order->id,
            $products,
            $deliveryDateMs
        );
        if (!$result['success']) {
            Log::warning('DigiPay deliver not completed after verify', [
                'order_id' => $order->id,
                'purchase_type' => $purchaseType,
                'trackingCode' => $trackingCode,
            ]);
        }
    }

    /**
     * @return array{success: bool, trackingCode: ?string, response: array<string, mixed>}
     */
    public function requestRefund(string $saleTrackingCode, string $refundProviderId, int $amount, int $ticketType = self::VERIFY_TYPE): array
    {
        if (!$this->accessToken) {
            Log::warning('DigiPay refund skipped: no access token');
            return ['success' => false, 'trackingCode' => null, 'response' => []];
        }

        $payload = [
            'providerId' => $refundProviderId,
            'amount' => $amount,
            'saleTrackingCode' => $saleTrackingCode,
        ];

        $httpResponse = Http::timeout(self::REQUEST_TIMEOUT_SECONDS)
            ->withHeaders([
                'Content-Type' => 'application/json; charset=UTF-8',
            ])
            ->withToken($this->accessToken)
            ->post($this->baseUrl() . '/refunds?type=' . $ticketType, $payload);

        $response = $httpResponse->json();
        if (!is_array($response)) {
            Log::warning('DigiPay refund invalid response', ['body' => $httpResponse->body()]);
            return ['success' => false, 'trackingCode' => null, 'response' => []];
        }

        if (($response['result']['status'] ?? null) !== 0) {
            Log::warning('DigiPay refund failed', ['response' => $response]);
            return ['success' => false, 'trackingCode' => null, 'response' => $response];
        }

        return [
            'success' => true,
            'trackingCode' => isset($response['trackingCode']) ? (string)$response['trackingCode'] : null,
            'response' => $response,
        ];
    }

    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        $url = $postData->getPostData()['redirectUrl'] ?? null;
        if (!$url) {
            abort(500, 'DigiPay redirect url is missing.');
        }
        header('Location: ' . $url);
        exit;
    }

    private function authorize(): void
    {
        try {
        if (!$this->clientId || !$this->clientSecret || !$this->username || !$this->password) {
            Log::warning('DigiPay OAuth missing credentials', [
                'has_client_id' => !empty($this->clientId),
                'has_client_secret' => !empty($this->clientSecret),
                'has_username' => !empty($this->username),
                'has_password' => !empty($this->password),
            ]);
            $this->accessToken = null;
            return;
        }

        $tokenUrl = $this->baseUrl() . '/oauth/token';
        $basicToken = base64_encode($this->clientId . ':' . $this->clientSecret);
        $requestData = [
            'username' => $this->username,
            'password' => $this->password,
            'grant_type' => 'password',
        ];
        $oauthResponse = Http::asForm()
            ->timeout(self::REQUEST_TIMEOUT_SECONDS)
            ->withHeaders([
                'Authorization' => 'Basic ' . $basicToken,
            ])
            ->post($tokenUrl, $requestData);

        $response = $oauthResponse->json();
        $this->accessToken = $response['access_token'] ?? null;

        if (!$this->accessToken) {
            $oauthResponse = Http::asMultipart()
                ->timeout(self::REQUEST_TIMEOUT_SECONDS)
                ->withHeaders([
                    'Authorization' => 'Basic ' . $basicToken,
                ])
                ->post($tokenUrl, $requestData);

            $response = $oauthResponse->json();
            $this->accessToken = $response['access_token'] ?? null;
        }

        if (!$this->accessToken) {
            Log::warning('DigiPay OAuth token not found', [
                'status' => $oauthResponse->status(),
                'body' => $oauthResponse->body(),
                'base_url' => $this->baseUrl(),
                'is_test' => $this->isTest,
            ]);
        }
        } catch (\Throwable $e) {
            Log::error('DigiPay OAuth error', ['message' => $e->getMessage()]);
            $this->accessToken = null;
        }
    }

    private function baseUrl(): string
    {
//        return $this->isTest ? self::TEST_API_BASE_URL : self::LIVE_API_BASE_URL;
        return self::LIVE_API_BASE_URL;
    }

    private function requestHeaders(): array
    {
        return [
            'Agent' => 'WEB',
            'Digipay-Version' => self::DIGIPAY_VERSION,
            'Content-Type' => 'application/json; charset=UTF-8',
        ];
    }

    private function normalizeMobile(?string $mobile): string
    {
        $mobile = trim((string)$mobile);
        if (str_starts_with($mobile, '+98')) {
            return '0' . substr($mobile, 3);
        }
        if (str_starts_with($mobile, '98')) {
            return '0' . substr($mobile, 2);
        }
        return $mobile;
    }
}

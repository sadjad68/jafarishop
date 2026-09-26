<?php

namespace App\Modules\Order\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Jobs\SnappPayInquiryJob;

class SnappPay implements PaymentGateway
{
    private string $base_url = 'https://api.snapppay.ir'; // آدرس پیش‌فرض
    private $ep_oauth_token = '/api/online/v1/oauth/token';
    private $ep_eligible = '/api/online/offer/v1/eligible';
    private $ep_request = '/api/online/payment/v1/token';
    private $ep_verify = '/api/online/payment/v1/verify';
    private $ep_settle = '/api/online/payment/v1/settle';
    private $ep_cancel = '/api/online/payment/v1/cancel';
    private $ep_update = '/api/online/payment/v1/update';
    private $ep_inquiry = '/api/online/payment/v1/status';
    private const FAILED_STATUSES = ["INIT", "REGISTERED", "REVERT", "CANCEL"];
    private const CALLBACK_ROUTE_NAME = 'basket.finish.snapp-pay';

    private ?string $client_id = null;
    private ?string $client_secret = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $access_token = null;

    public function __construct($bank, $site_name)
    {
        if ($bank && isset($bank['config'])) {
            $cfg = is_array($bank['config']) ? $bank['config'] : json_decode($bank['config'], true);
            // خواندن base_url از کانفیگ در صورت وجود
            if (!empty($cfg['base_url'])) {
                $this->base_url = rtrim($cfg['base_url'], '/');
            }
            $this->client_id = $cfg['client_id'] ?? null;
            $this->client_secret = $cfg['client_secret'] ?? null;
            $this->username = $cfg['username'] ?? null;
            $this->password = $cfg['password'] ?? null;
            $this->site_name = $site_name;
            // مقداردهی سایر اندپوینت‌ها (اگر در دیتابیس ست شده باشند اولویت دارند، در غیر این صورت با base_url ترکیب می‌شوند)
            $this->ep_oauth_token = $this->prepareUrl($cfg['ep_oauth_token'] ?? $this->ep_oauth_token);
            $this->ep_eligible = $this->prepareUrl($cfg['ep_eligible'] ?? $this->ep_eligible);
            $this->ep_request = $this->prepareUrl($cfg['ep_request'] ?? $this->ep_request);
            $this->ep_verify = $this->prepareUrl($cfg['ep_verify'] ?? $this->ep_verify);
            $this->ep_settle = $this->prepareUrl($cfg['ep_settle'] ?? $this->ep_settle);
            $this->ep_cancel = $this->prepareUrl($cfg['ep_cancel'] ?? $this->ep_cancel);
            $this->ep_update = $this->prepareUrl($cfg['ep_update'] ?? $this->ep_update);
            $this->ep_inquiry = $this->prepareUrl($cfg['ep_inquiry'] ?? $this->ep_inquiry);
        }
        // دریافت توکن
        $this->getBearerToken();
    }
    /**
     * متد کمکی برای بررسی اینکه آیا آدرس کامل است یا نیاز به پیشوند base_url دارد
     */
    private function prepareUrl(string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return $url;
        }
        return $this->base_url . '/' . ltrim($url, '/');
    }
    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        try {
            $order->loadMissing('bank', 'items.product.categories', 'user', 'discount');

            $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);

            if (!$this->checkEligibleAmount($paymentToman) || @$this->checkEligibleAmount($paymentToman)['response']['eligible'] != true ) {
                return PaymentPostDTO::fromData('failed');
            }
            $txId = $this->generateUniqueTxId();
            $orderItems = $order->items;
            $cartItemsArray = [];
            $totalAmount = 0;

            foreach ($orderItems as $item) {
                $item_amount = GatewayAmountHelper::tomanToRial(GatewayAmountHelper::itemUnitToman($item, $order));
                $cartItemsArray[] = [
                    'amount' => $item_amount,
                    'category' => @$item->product->categories[0]->title,
                    'count' => $item->quantity,
                    'id' => $item->product_id,
                    'name' => @$item->product->title,
                    'commissionType' => '100',
                ];
                $totalAmount += $item_amount * $item->quantity;
            }

            $amountRial = GatewayAmountHelper::tomanToRial($paymentToman);

            $cartListJson = [[
                'cartId' => $order->id . '13941364',
                'cartItems' => $cartItemsArray,
                'isShipmentIncluded' => true,
                'isTaxIncluded' => true,
                'shippingAmount' => GatewayAmountHelper::tomanToRial(GatewayAmountHelper::normalizeToman($order->shipping_price)),
                'taxAmount' => GatewayAmountHelper::tomanToRial(GatewayAmountHelper::normalizeToman($order->tax_price)),
                'totalAmount' => intval($totalAmount)
            ]];
            $firstNinePosition = strpos($order->user->mobile, '9');
            $mobile = substr($order->user->mobile, $firstNinePosition);
            $userMobile = '+98' . $mobile;

            $payload = [
                "amount" => $amountRial,
                "cartList" => $cartListJson,
                "discountAmount" => GatewayAmountHelper::tomanToRial(GatewayAmountHelper::normalizeToman($order->discount_price)),
                "externalSourceAmount" => 0,
                "mobile" => $userMobile,
                "paymentMethodTypeDto" => "INSTALLMENT",
                "returnURL" => route(self::CALLBACK_ROUTE_NAME),
                "transactionId" => $txId,
            ];

            Log::info('SnappPay payment request', [
                'order_id' => $order->id,
                'url' => $this->ep_request,
                'payload' => $payload,
            ]);

            $response = Http::withToken($this->access_token)
                ->acceptJson()
                ->timeout(7200)
                ->post($this->ep_request, $payload)
                ->json();

            Log::info('SnappPay payment response', [
                'order_id' => $order->id,
                'url' => $this->ep_request,
                'response' => $response,
            ]);

            if (@$response['response']['paymentToken']) {
                return PaymentPostDTO::fromData('success', [
                    'paymentToken' => $response['response']['paymentToken'] ?? null,
                    'transactionId' => $txId,
                    'paymentPageUrl' => $response['response']['paymentPageUrl'] ?? null,
                ]);
            }

            return PaymentPostDTO::fromData('failed');

        } catch (\Throwable $e) {
            Log::error('SnappPay getBankToken error', ['message' => $e->getMessage()]);
            return PaymentPostDTO::fromData('failed');
        }
    }

    public function verifyTransaction(array $callbackData, int $price, Order $order): PaymentVerifyDTO
    {
        try {
            if (@$callbackData['state'] !== "OK") {
                return PaymentVerifyDTO::fromData('failed');
            }
            return $this->verify($order);
        } catch (\Throwable $e) {
            Log::error('SnappPay verifyTransaction error', ['message' => $e->getMessage()]);
            return PaymentVerifyDTO::fromData('failed');
        }
    }

    public function redirect(PaymentPostDTO $postData, Order $order)
    {
        $paymentPageUrl = $postData->getPostData()['paymentPageUrl'] ?? null;

        Log::info('SnappPay redirect to payment page', [
            'order_id' => $order->id,
            'paymentPageUrl' => $paymentPageUrl,
            'post_data' => $postData->getPostData(),
        ]);

        header("refresh:0;url=" . $paymentPageUrl);
    }
    public function cancel(Order $order)
    {
        try {
            $transaction = json_decode($order->transaction_info, true);
            $paymentToken = $transaction['post']['paymentToken'];
            $cancelResp = Http::withToken($this->access_token)
                ->acceptJson()
                ->timeout(7200)
                ->post($this->ep_cancel, ['paymentToken' => $paymentToken])
                ->json();
            if (@$cancelResp['successful'] == false) {
                return PaymentCancelDTO::fromData('false', $cancelResp['errorData']);
            }
            return PaymentCancelDTO::fromData('success');
        }
        catch (\Throwable $e) {
            Log::error('SnappPay cancel error', ['message' => $e->getMessage()]);
            return PaymentCancelDTO::fromData('failed');
        }
    }

    public function update(Order $order)
    {
        $transaction = json_decode($order->transaction_info, true);
        $paymentToken = $transaction['post']['paymentToken'];
        try {
            $order->loadMissing('bank', 'items.product.categories', 'discount');

            $payload = GatewayAmountHelper::buildSnappPayCartPayload($order, true);

            $cartListJson = [
                [
                    'cartId' => $order->id,
                    'cartItems' => $payload['cartItems'],
                    'isShipmentIncluded' => true,
                    'isTaxIncluded' => true,
                    'shippingAmount' => $payload['shippingRial'],
                    'taxAmount' => $payload['taxRial'],
                    'totalAmount' => $payload['itemsTotalToman'] > 0
                        ? GatewayAmountHelper::tomanToRial($payload['itemsTotalToman'])
                        : 0,
                ],
            ];

            $post_data = [
                "amount" => $payload['amountRial'],
                "cartList" => $cartListJson,
                "discountAmount" => $payload['discountRial'],
                "externalSourceAmount" => 0,
                "paymentMethodTypeDto" => "INSTALLMENT",
                "paymentToken" => $paymentToken,
                "transactionId" => $order->transactionId,
            ];
            $updateResp = Http::withToken($this->access_token)
                ->acceptJson()
                ->timeout(7200)
                ->post($this->ep_update, $post_data)
                ->json();
            if (@$updateResp['successful'] == false) {
                return PaymentCancelDTO::fromData('false', $updateResp['errorData']);
            }
            return PaymentCancelDTO::fromData('success');
        }
        catch (\Throwable $e) {
            Log::error('SnappPay cancel error', ['message' => $e->getMessage()]);
            return PaymentCancelDTO::fromData('failed');
        }
    }
    public function generateUniqueTxId()
    {
        do {
            $txId = 'tx_' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Order::where('transaction_info->post->transactionId', $txId)->exists());

        return $txId;
    }

    public function getBearerToken(): void
    {
        $basicKey = base64_encode("{$this->client_id}:{$this->client_secret}");

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic ' . $basicKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->timeout(60)
                ->post($this->ep_oauth_token, [
                    'grant_type' => 'password',
                    'scope' => 'online-merchant',
                    'username' => $this->username,
                    'password' => $this->password,
                ])
                ->json();

            $this->access_token = $response['access_token'] ?? null;

            if (!$this->access_token) {
                Log::error('SnappPay OAuth: access_token missing', ['resp' => $response]);
                throw new \RuntimeException('SnappPay OAuth failed');
            }
        } catch (\Throwable $e) {
            Log::error('SnappPay OAuth error', ['message' => $e->getMessage()]);
            // اجازه می‌دهیم نال بماند تا درخواست‌های بعدی fail شوند و لاگ ثبت شود
            $this->access_token = null;
        }
    }
    public function eligible($price){
        return $this->checkEligibleAmount($price);
    }
    private function checkEligibleAmount($price)
    {
        try {
            $resp = Http::withToken($this->access_token)
                ->get($this->ep_eligible, ['amount' => $price * 10])
                ->json();
            return $resp;
        } catch (\Throwable $e) {
            Log::error('SnappPay eligible check error', ['message' => $e->getMessage()]);
            return false;
        }
    }

//    private function checkEligibleAmount($price): bool
//    {
//        try {
//            $amount = $price * 10;
//            $resp = Http::withToken($this->access_token)
//                ->acceptJson()
//                ->timeout(60)
//                ->get($this->ep_eligible, ['amount' => intval($amount)])
//                ->json();
//
//            return isset($resp['successful']) && $resp['successful'] === true;
//        } catch (\Throwable $e) {
//            Log::error('SnappPay eligible check error', ['message' => $e->getMessage()]);
//            return false;
//        }
//    }
//    public function verify($order){
//        $transaction_order = json_decode($order->transaction_info, true);
//        $paymentToken = $transaction_order['post']['paymentToken'] ?? null;
//
//        if (!$paymentToken) {
//            Log::warning('SnappPay verify: missing paymentToken in transaction_info');
//            return PaymentVerifyDTO::fromData('failed');
//        }
//
//        // VERIFY
//        $verifyResp = Http::withToken($this->access_token)
//            ->acceptJson()
//            ->timeout(7200)
//            ->post($this->ep_verify, ['paymentToken' => $paymentToken])
//            ->json();
//
//        Log::info('SnappPay VERIFY response', ['resp' => $verifyResp]);
//
//        $verifySuccessful = @$verifyResp['successful'] === true;
//        $verifyStatus = @$verifyResp['response']['status'];
//
//        if (!$verifySuccessful || ($verifyStatus && in_array($verifyStatus, self::FAILED_STATUSES, true))) {
//            Log::info('SnappPay order cancel or not successful at verify', ['status' => $verifyStatus]);
//            return PaymentVerifyDTO::fromData('failed');
//        }
//
//        $settleResp = Http::withToken($this->access_token)
//            ->acceptJson()
//            ->timeout(7200)
//            ->post($this->ep_settle, ['paymentToken' => $paymentToken])
//            ->json();
//
//        Log::info('SnappPay SETTLE response', ['resp' => $settleResp]);
//
//        if (!@$settleResp['successful']) {
//            return PaymentVerifyDTO::fromData('failed');
//        }
//
//        return PaymentVerifyDTO::fromData('success');
//    }
    // جدید
    public function verify($order)
    {
        $info = json_decode($order->transaction_info, true);
        $paymentToken = $info['post']['paymentToken'] ?? null;

        if (!$paymentToken) {
            Log::warning('SnappPay verify: missing paymentToken');
            return PaymentVerifyDTO::fromData('failed');
        }

        try {
            $verifyResp = Http::withToken($this->access_token)
                ->acceptJson()
                ->timeout(60)
                ->post($this->ep_verify, ['paymentToken' => $paymentToken])
                ->json();


            Log::info('SnappPay VERIFY response', ['resp' => $verifyResp]);
        } catch (\Throwable $e) {
            Log::warning('SnappPay VERIFY timeout/exception', [
                'error' => $e->getMessage()
            ]);
            $verifyResp = null;
        }

        $verifySuccessful = $verifyResp['successful'] ?? false;
        $verifyStatus     = $verifyResp['response']['status'] ?? null;

        // ❗ verify ناموفق یا بی‌جواب → inquiry
        if (
            !$verifySuccessful ||
            ($verifyStatus && in_array($verifyStatus, self::FAILED_STATUSES, true))
        ) {

            $inq = $this->inquiry($paymentToken);
            Log::info('SnappPay INQUIRY response', ['resp' => $inq]);

            if (!$inq || !($inq['successful'] ?? false)) {
                return PaymentVerifyDTO::fromData('failed');
            }

            $inqStatus = $inq['response']['status'] ?? null;

            if (in_array($inqStatus, ['VERIFY', 'PENDING'], true)) {
                // retry verify
                return $this->verify($order);
            }

            if ($inqStatus === 'SETTLE') {
                return $this->settle($paymentToken);
            }

            if (in_array($inqStatus, self::FAILED_STATUSES, true)) {
                return PaymentVerifyDTO::fromData('failed');
            }
        }

        // ✅ verify موفق → settle
        return $this->settle($paymentToken);
    }

    //
//
//
//    public function inquiry(Order $order)
//    {
//        try {
//            $transaction = json_decode($order->transaction_info, true);
//            $paymentToken = $transaction['post']['paymentToken'];
//            $inquiryResp = Http::withToken($this->access_token)
//                ->acceptJson()
//                ->timeout(7200)
//                ->get($this->ep_inquiry, ['paymentToken' => $paymentToken])
//                ->json();
//            return $inquiryResp;
//
//        }
//        catch (\Throwable $e) {
//            Log::error('SnappPay inquiry error', ['message' => $e->getMessage()]);
//        }
//    }
//جدید
    public function inquiry(string $paymentToken)
    {
        try {
            Log::info('$inqsssssssss');
            return Http::withToken($this->access_token)
                ->acceptJson()
                ->timeout(15)
                ->get($this->ep_inquiry, ['paymentToken' => $paymentToken])
                ->json();
        } catch (\Throwable $e) {
            Log::error('SnappPay inquiry error', ['message' => $e->getMessage()]);
            return null;
        }
    }

//

    public function settle(string $paymentToken)
    {
        try {
            $settleResp = Http::withToken($this->access_token)
                ->acceptJson()
                ->timeout(20)
                ->post($this->ep_settle, ['paymentToken' => $paymentToken])
                ->json();
        } catch (\Throwable $e) {
            Log::warning('Settle timeout — using inquiry');
            $settleResp = null;
        }

        $successful = $settleResp['successful'] ?? false;
        Log::info('SnappPay SETTLE response', ['resp' => $settleResp]);
        // ❗ اگر settle موفق نبود → inquiry
        if (!$successful) {

            $inq = $this->inquiry($paymentToken);

            if (!$inq) {

                return PaymentVerifyDTO::fromData('failed');
            }

            $status = $inq['response']['status'] ?? null;

            if ($status === 'VERIFY') {
                return $this->settle($paymentToken); // retry settle
            }

            if ($status === 'SETTLE') {
                Log::info('SnappPay already settled', ['resp' => $inq]);
                return PaymentVerifyDTO::fromData('success');
            }

            if (in_array($status, self::FAILED_STATUSES, true)) {
                Log::info('SnappPay failed settled', ['resp' => $inq]);
                return PaymentVerifyDTO::fromData('failed');
            }
        }
        Log::info('SnappPay settled', ['resp' => $settleResp]);
        $stat = $successful ? 'success' : 'failed';
        return PaymentVerifyDTO::fromData($stat);
    }



    //
}

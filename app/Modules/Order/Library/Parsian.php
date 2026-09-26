<?php

namespace App\Modules\Order\Library;

use App\Modules\Order\Entities\Order;
use SoapClient;

class Parsian implements PaymentGateway
{
    private string $loginAccount;
    private string $site_name;

    private const EP_SALE_WSDL = 'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?wsdl';
    private const EP_CONFIRM_WSDL = 'https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?wsdl';
    private const EP_REDIRECT = 'https://pec.shaparak.ir/NewIPG/?token=';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.parsian';

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->loginAccount = (string)($config['LoginAccount'] ?? $config['pin'] ?? '');
        $this->site_name = $site_name;
    }

    public function getBankToken($amount, Order $order): PaymentPostDTO
    {
        if (empty($this->loginAccount)) {
            return PaymentPostDTO::fromData('failed');
        }

        $client = $this->makeSoapClient(self::EP_SALE_WSDL);

        if (!is_object($client)) {
            return PaymentPostDTO::fromData('failed');
        }

        $requestData = [
            'LoginAccount' => $this->loginAccount,
            'OrderId' => (int)$order->id, // [cite: 259]
            'Amount' => GatewayAmountHelper::tomanToRial(
                GatewayAmountHelper::resolvePaymentToman((int) $amount, $order)
            ),
            'CallBackUrl' => route(self::CALLBACK_ROUTE_NAME), // [cite: 259]
            'AdditionalData' => '',
            'Originator' => '',
        ];

        try {
            // مطابق فایل نمونه PecRequestClass.php، نام متد SalePaymentRequest است
            $response = $client->SalePaymentRequest([
                'requestData' => $requestData
            ]);

            // مطابق فایل نمونه، نتیجه در فیلد SalePaymentRequestResult قرار دارد
            $result = $response->SalePaymentRequestResult ?? null;
            $status = isset($result->Status) ? (int)$result->Status : -1;
            $token = $result->Token ?? null;

            // وضعیت 0 به معنای موفقیت در صدور توکن است [cite: 268, 271]
            if ($status === 0 && !empty($token)) {
                return PaymentPostDTO::fromData('success', ['token' => $token]);
            }
        } catch (\Throwable $e) {
            // در صورت نیاز به دیباگ: dd($e->getMessage());
        }

        return PaymentPostDTO::fromData('failed');
    }

    public function verifyTransaction(array $callbackData, int $price, Order $order): PaymentVerifyDTO
    {
        // استخراج اطلاعات ارسالی از سمت بانک در CallBack [cite: 329, 332]
        $status = (int)($callbackData['status'] ?? $callbackData['Status'] ?? -1);
        $token = $callbackData['Token'] ?? $callbackData['token'] ?? null;
        $rrn = $callbackData['RRN'] ?? $callbackData['rrn'] ?? null;

        // بررسی موفقیت اولیه تراکنش در CallBack قبل از تایید [cite: 335, 338]
        if ($status !== 0 || empty($token) || (int)$rrn <= 0) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $client = $this->makeSoapClient(self::EP_CONFIRM_WSDL);
        if (!$client) {
            return PaymentVerifyDTO::fromData('failed');
        }

        $requestData = [
            'LoginAccount' => $this->loginAccount,
            'Token' => (int)$token,
        ];

        try {
            // فراخوانی متد تایید تراکنش (Confirm)
            $response = $client->ConfirmPayment(['requestData' => $requestData]);
            $result = $response->ConfirmPaymentResult ?? null;
            $confirmStatus = isset($result->Status) ? (int)$result->Status : -1;
            $confirmRrn = $result->RRN ?? $rrn;

            // اگر وضعیت تایید 0 باشد، تراکنش قطعی است
            if ($confirmStatus === 0) {
                return PaymentVerifyDTO::fromData('success', [
                    'RRN' => $confirmRrn,
                    'Token' => $token,
                    'CardNumberMasked' => $result->CardNumberMasked ?? null,
                ]);
            }
        } catch (\Throwable $e) {
            // خطا در ارتباط با سرویس تایید
        }

        return PaymentVerifyDTO::fromData('failed');
    }

    public function redirect(PaymentPostDTO $postData, Order $order)
    {
        $token = $postData->getPostData()['token'] ?? null;
        if (empty($token)) {
            abort(400, 'Payment token missing.');
        }

        // هدایت کاربر به صفحه پرداخت با توکن [cite: 271, 273]
        header('Location: ' . self::EP_REDIRECT . urlencode((string)$token));
        exit;
    }

    private function makeSoapClient(string $wsdl): ?SoapClient
    {
        try {
            return new SoapClient($wsdl, [
                'cache_wsdl' => WSDL_CACHE_NONE, // غیرفعال سازی کش برای جلوگیری از خطای متد
                'trace' => true,
                'exceptions' => true,
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

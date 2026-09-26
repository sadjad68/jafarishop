<?php

namespace App\Modules\Order\Library;

use Exception;
use Illuminate\Support\Facades\Http;
use App\Modules\Order\Entities\Order;

class Sadad implements PaymentGateway
{
    private $key;
    private $TerminalId;
    private $merchant;
    private $site_name;
    private const EP_REQUEST  = 'https://sadad.shaparak.ir/vpg/api/v0/Request/PaymentRequest';
    private const EP_VERIFY   = 'https://sadad.shaparak.ir/vpg/api/v0/Advice/Verify';
    private const EP_REDIRECT = 'https://sadad.shaparak.ir/VPG/Purchase';
    private const CALLBACK_ROUTE_NAME = 'basket.finish.sadad-bank';
    private const USER_AGENT  = 'Sadad Rest Api v1';

    public function __construct($bank, $site_name)
    {
        $config = json_decode(@$bank['config'] ?? '[]', true);
        $this->merchant   = $config['MerchantId'] ?? null;
        $this->key        = $config['key'] ?? null;
        $this->TerminalId = $config['TerminalId'] ?? null;
        $this->site_name  = $site_name;
    }

    public function getBankToken($amount, $order): PaymentPostDTO
    {
        $paymentToman = GatewayAmountHelper::resolvePaymentToman((int) $amount, $order);
        $amount        = GatewayAmountHelper::tomanToRial($paymentToman);
        $OrderId       = $order->id;
        $TerminalId    = $this->TerminalId;
        $key           = $this->key;
        $merchant      = $this->merchant;
        $LocalDateTime = date("m/d/Y g:i:s a");
        $ReturnUrl     = route(self::CALLBACK_ROUTE_NAME);

        $SignData = self::encrypt_pkcs7("$TerminalId;$OrderId;$amount", "$key");

        $data = [
            'TerminalId'    => $TerminalId,
            'MerchantId'    => $merchant,
            'Amount'        => $amount,
            'SignData'      => $SignData,
            'ReturnUrl'     => $ReturnUrl,
            'LocalDateTime' => $LocalDateTime,
            'OrderId'       => $OrderId,
        ];

        $result = self::CallAPI(self::EP_REQUEST, $data);

        if (@$result['ResCode'] != 0) {
            \Log::info(@$result['Description']);
            return PaymentPostDTO::fromData('failed');
        }

        return PaymentPostDTO::fromData('success', ['token' => $result['Token']]);
    }

    public function verifyTransaction($callbackData, $price = null, $order = null): PaymentVerifyDTO
    {
        $key = $this->key;
        $verifyData = [
            'Token'    => $callbackData['token'],
            'SignData' => self::encrypt_pkcs7($callbackData['token'], $key),
        ];

        $result = self::CallAPI(self::EP_VERIFY, $verifyData);

        \Log::info('72');
        \Log::info($result);
        if ($result && ($result['ResCode'] != -1 && $result['ResCode'] == 0 || $result['ResCode'] == 100)) {
            return PaymentVerifyDTO::fromData('success',
                [
                    'RetrivalRefNo'=>$result['RetrivalRefNo'],
                    'SystemTraceNo'=>$result['SystemTraceNo'],
                ]
            );
        }

        return PaymentVerifyDTO::fromData('failed');
    }

    public static function encrypt_pkcs7($str, $key)
    {
        $key = base64_decode($key);
        $ciphertext = OpenSSL_encrypt($str, "DES-EDE3", $key, OPENSSL_RAW_DATA);
        return base64_encode($ciphertext);
    }

    public static function CallAPI($url, $data = false)
    {
        try {
            $request = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
                'User-Agent'   => self::USER_AGENT,
            ])->withOptions([
                'verify' => false,
            ])->timeout(30);

            $response = $request->send('POST', $url, [
                'json' => $data ?: new \stdClass(),
            ]);

            $body = $response->body();
            return !empty($body) ? json_decode($body,true) : false;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function redirect(PaymentPostDTO $postData, Order $order): void
    {
        $token = $postData->getPostData()['token'];
        $url   = self::EP_REDIRECT;

        echo "
            <form name='myform' action='{$url}' method='GET'>
                <input type='hidden' id='Token' name='Token' value='{$token}'>
            </form>
            <script>window.onload = function() { document.forms[0].submit(); }</script>
        ";
        exit;
    }
}

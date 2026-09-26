<?php

namespace App\Modules\Order\Services;

use App\Library\SiteHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Library\PaymentGatewayFactory;

class BankPaymentService
{
    public function handlePayment(Bank $bank, Order $order, int $price)
    {
        $site = SiteHelper::getInformation();
        $gateway = PaymentGatewayFactory::create($bank, $site['site_name'] ?? '-');
        $response = $gateway->getBankToken($price, $order);
        Auth::loginUsingId($order->user_id);
//        if($order->user->mobile !== "09032783528"){
//            return redirect(route('basket.failed'))
//                ->with('error', 'بخش پرداخت سایت درحال بروزرسانی میباشد.لطفا یک ساعت دیگر مجدد تلاش کنید.');
//        }
        if ($response->getStatus() === 'failed') {
            Log::error('Bank payment token failed; order deleted', [
                'order_id' => $order->id,
                'bank_title' => $bank->title,
                'bank_type' => $bank->bank_type,
                'price' => $price,
                'gateway_status' => $response->getStatus(),
                'gateway_response' => $response->getPostData(),
            ]);
            $order->delete();
            return redirect(route('basket.failed'))
                ->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
        }
        $order->update([
            'transaction_info' => [
                'post' => $response->getPostData(),
                'verify' => []
            ]
        ]);
        $gateway->redirect($response, $order);
    }

    public function handleVerify(Order $order, array $callbackData, int $price): bool
    {
        $site = SiteHelper::getInformation();
        $gateway = PaymentGatewayFactory::create($order->bank, $site['site_name'] ??'');
        $response = $gateway->verifyTransaction($callbackData, $price, $order);
        $verifyData = $response->getVerifyData() ?? [];
        Log::info('handleVerify', [
            'order_id' => $order->id,
            'gateway_status' => $response->getStatus(),
            'verify_data' => $verifyData,
            'callback_data' => $callbackData,
        ]);
        Auth::loginUsingId($order->user_id);
        $transaction_info = json_decode($order->transaction_info, true);
        $transaction_info['verify'] = $verifyData;
        $order->update([
            'transaction_info' => $transaction_info,
        ]);
        if ($response->getStatus() === 'failed') {
            Log::error('Bank payment verify failed; order deleted', [
                'order_id' => $order->id,
                'price' => $price,
                'failure_reason' => $verifyData['reason'] ?? null,
                'failure_message' => $verifyData['message'] ?? null,
                'gateway_http_status' => $verifyData['http_status'] ?? null,
                'gateway_code' => $verifyData['gateway_status'] ?? null,
                'callback_data' => $callbackData,
            ]);
            $order->delete();
            return false;
        }
        return true;
    }
}

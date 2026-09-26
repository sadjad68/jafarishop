<?php

namespace App\Modules\Order\Services;

use App\Library\SiteHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\OrderItem;
use App\Modules\Order\Library\DigiPay;
use App\Modules\Order\Library\PaymentGatewayFactory;
use App\Modules\Order\Library\SnappPay;
use App\Modules\Product\DTO\ProductDTO;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;


class ReturnService
{
    private const DIGIPAY_DEFAULT_PURCHASE_TYPE = 5;

    public function returnWithCount($item,$quantity){
        $returned = intval($item->quantity) - intval($quantity);
        $item ->update([
           'quantity' =>intval($quantity) ,
            'old_quantity'=>intval($item->quantity)
        ]);
        self::reverseStockAndPrice($item,$returned);
    }
    public static function reverseStockAndPrice($item,$quantity)
    {
        $product = Product::find(@$item->product_id);

        $product_variant_check = null;
        if ($item->product_variant_id != null) {

            $product_variant_check = ProductVariant::find($item->product_variant_id);
            $product_variant_check->update([
                'stock' => intval(@$product_variant_check->stock) + intval($quantity),
            ]);
            $product_variant_check->save();
            $sum_stock = $product->variants()->orderBy('final_price', 'ASC')->sum('stock');
            //Review : اگر یک محصول با متغییر فقط یک موجودی داشته باشه این خط خطا میداد
            $minimum_price_variant = $product->variants()->where('price_affective', '1')->orderBy('final_price', 'ASC')->where('stock', '<>', '0')->first();
            if ($minimum_price_variant == null) {
                $minimum_price_variant = $product->variants()->where('price_affective', '1')->orderBy('final_price', 'ASC')->first();
            }
            $product->update(
                [
                    'price' => $minimum_price_variant['price'],
                    'discounted_price' => $minimum_price_variant['discounted_price'],
                    'final_price' => $minimum_price_variant['final_price'],
                    'stock' => $sum_stock,
                ]
            );
        } else {
            $product->update([
                'stock' => intval(@$product->stock) + intval($quantity),
            ]);
        }
        $product->save();
    }
    public function snappPayCancelation($order){
        $site = SiteHelper::getInformation();
        $bank = Bank::findOrfail($order['bank_id']);
        $cancelation = new SnappPay($bank,$site['site_name']);
        return $cancelation->cancel($order);
    }
    public function snappPayUpdate($order){
        $site = SiteHelper::getInformation();
        $bank = Bank::findOrfail($order['bank_id']);
        $cancelation = new SnappPay($bank,$site['site_name']);
        return $cancelation->update($order);
    }

    public function digiPayVerifiedApiAmount(Order $order): int
    {
        $tx = $this->transactionInfoArray($order);
        $verify = $tx['verify'] ?? [];

        return (int) ($verify['amount'] ?? 0);
    }

    public function digiPayRefundedApiTotal(Order $order): int
    {
        $tx = $this->transactionInfoArray($order);

        return (int) ($tx['digipay_refunded_api_total'] ?? 0);
    }

    public function digiPayRemainingApiRefundable(Order $order): int
    {
        $total = $this->digiPayVerifiedApiAmount($order);
        $done = $this->digiPayRefundedApiTotal($order);

        return max(0, $total - $done);
    }

    public function digiPayRefundApiAmountFromMerchandiseTomans(Order $order, int $refundMerchandiseTomans): int
    {
        if ($refundMerchandiseTomans <= 0) {
            return 0;
        }
        $apiTotal = $this->digiPayVerifiedApiAmount($order);
        $paidTomans = $this->orderPaidAmountTomans($order);
        $remaining = $this->digiPayRemainingApiRefundable($order);
        if ($apiTotal <= 0 || $paidTomans <= 0 || $remaining <= 0) {
            return 0;
        }

        $computed = (int) round($apiTotal * $refundMerchandiseTomans / $paidTomans);

        return min($remaining, max(1, $computed));
    }

    public function orderItemRefundMerchandiseTomans(OrderItem $item, int $returnedQuantity): int
    {
        if ($returnedQuantity <= 0) {
            return 0;
        }
        $unit = (int) ($item->discounted_price != 0 ? $item->discounted_price : $item->price);
        $order = $item->order;
        $tariff = floatval($order->gateway_tariff ?? 0);
        $unitWithTariff = DiscountService::applyGatewayTariff($unit, $tariff);

        return $unitWithTariff * $returnedQuantity;
    }

    /**
     * @return array{success: bool, trackingCode: ?string, response: array, message: ?string}
     */
    public function digiPayRefundForOrder(Order $order, int $refundAmountApiUnits, string $refundProviderSuffix = ''): array
    {
        if ($refundAmountApiUnits <= 0) {
            return ['success' => true, 'trackingCode' => null, 'response' => [], 'message' => null];
        }
        $tx = $this->transactionInfoArray($order);
        $verify = $tx['verify'] ?? [];
        $saleTracking = $verify['trackingCode'] ?? null;
        if (!$saleTracking) {
            Log::warning('DigiPay refund skipped: no sale trackingCode on order', ['order_id' => $order->id]);

            return [
                'success' => false,
                'trackingCode' => null,
                'response' => [],
                'message' => 'کد پیگیری دیجی‌پی برای این سفارش ثبت نشده است.',
            ];
        }
        $purchaseType = (int) ($verify['digipayPurchaseType'] ?? self::DIGIPAY_DEFAULT_PURCHASE_TYPE);
        $site = SiteHelper::getInformation();
        $bank = Bank::findOrFail($order->bank_id);
        $gateway = PaymentGatewayFactory::create($bank, $site['site_name']);
        if (!$gateway instanceof DigiPay) {
            Log::error('DigiPay refund: factory did not return DigiPay', ['order_id' => $order->id]);

            return [
                'success' => false,
                'trackingCode' => null,
                'response' => [],
                'message' => 'درگاه دیجی‌پی برای این سفارش یافت نشد.',
            ];
        }
        $suffix = $refundProviderSuffix !== '' ? $refundProviderSuffix : uniqid('', true);
        $providerId = (string) $order->id . '-refund-' . $suffix;
        $result = $gateway->requestRefund((string) $saleTracking, $providerId, $refundAmountApiUnits, $purchaseType);
        $message = null;
        if (!$result['success']) {
            $message = $result['response']['result']['message'] ?? 'خطا در بازگشت وجه دیجی‌پی';
        } elseif ($refundAmountApiUnits > 0) {
            $fresh = Order::query()->find($order->id);
            if ($fresh !== null) {
                $merged = $this->transactionInfoArray($fresh);
                $merged['digipay_refunded_api_total'] = (int) ($merged['digipay_refunded_api_total'] ?? 0) + $refundAmountApiUnits;
                $fresh->update(['transaction_info' => $merged]);
            }
        }

        return [
            'success' => $result['success'],
            'trackingCode' => $result['trackingCode'] ?? null,
            'response' => $result['response'],
            'message' => $message,
        ];
    }

    private function transactionInfoArray(Order $order): array
    {
        $raw = $order->transaction_info;
        if (is_array($raw)) {
            return $raw;
        }

        return json_decode((string) $raw, true) ?? [];
    }

    private function orderPaidAmountTomans(Order $order): int
    {
        $deposit = (int) str_replace(',', '', (string) $order->deposit_price);

        return $deposit !== 0 ? $deposit : (int) str_replace(',', '', (string) $order->payment_price);
    }
}

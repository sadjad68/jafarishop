<?php

namespace App\Modules\Order\Library;

use App\Modules\Order\Entities\Order;
use App\Modules\Order\Services\DiscountService;

class GatewayAmountHelper
{
    public static function resolvePaymentToman(int $fallbackAmount, Order $order): int
    {
        $deposit = self::normalizeToman($order->deposit_price);
        if ($deposit > 0) {
            return $deposit;
        }

        $deposit = self::normalizeToman($order->deposit_price);
        if ($deposit > 0) {
            return $deposit;
        }

        $payment = self::normalizeToman($order->payment_price);

        return $payment > 0 ? $payment : $fallbackAmount;
    }

    public static function itemUnitToman($item, Order $order): int
    {
        return intval($item->discounted_price) != 0
            ? intval($item->discounted_price)
            : intval($item->price);
    }

    public static function tomanToRial(int $toman): int
    {
        return intval($toman . '0');
    }

    public static function normalizeToman($value): int
    {
        return intval(str_replace(',', '', (string) $value));
    }

    /**
     * @return array{
     *     cartItems: array<int, array<string, mixed>>,
     *     itemsTotalToman: int,
     *     shippingRial: int,
     *     taxRial: int,
     *     discountRial: int,
     *     amountRial: int
     * }
     */
    public static function buildSnappPayCartPayload(Order $order, bool $forUpdate = false): array
    {
        $cartItems = [];
        $itemsTotalToman = 0;

        foreach ($order->items as $item) {
            if ((int) $item->quantity <= 0) {
                continue;
            }

            $unitToman = self::itemUnitToman($item, $order);
            $itemRial = self::tomanToRial($unitToman);
            $cartItems[] = [
                'amount' => $itemRial,
                'category' => @$item->product->categories[0]->title,
                'count' => (int) $item->quantity,
                'id' => $item->product_id,
                'name' => @$item->product->title,
                'commissionType' => '100',
            ];
            $itemsTotalToman += $unitToman * (int) $item->quantity;
        }

        $shippingToman = self::normalizeToman($order->shipping_price);
        $taxToman = self::normalizeToman($order->tax_price);
        $discountToman = $forUpdate
            ? self::proportionalDiscountToman($order, $itemsTotalToman)
            : self::normalizeToman($order->discount_price);

        $basePaymentToman = max(0, $itemsTotalToman + $shippingToman + $taxToman - $discountToman);
        $paymentToman = $forUpdate
            ? DiscountService::applyGatewayTariff($basePaymentToman, floatval($order->gateway_tariff ?? 0))
            : self::resolvePaymentToman($basePaymentToman, $order);

        return [
            'cartItems' => $cartItems,
            'itemsTotalToman' => $itemsTotalToman,
            'shippingRial' => self::tomanToRial($shippingToman),
            'taxRial' => self::tomanToRial($taxToman),
            'discountRial' => self::tomanToRial($discountToman),
            'amountRial' => self::tomanToRial($paymentToman),
        ];
    }

    private static function proportionalDiscountToman(Order $order, int $currentItemsTotalToman): int
    {
        $orderDiscount = self::normalizeToman($order->discount_price);
        if ($orderDiscount <= 0 || $currentItemsTotalToman <= 0) {
            return 0;
        }

        $originalItemsTotalToman = 0;
        foreach ($order->items as $item) {
            $qty = (int) ($item->old_quantity ?: $item->quantity);
            if ($qty <= 0) {
                continue;
            }
            $originalItemsTotalToman += self::itemUnitToman($item, $order) * $qty;
        }

        if ($originalItemsTotalToman <= 0) {
            return $orderDiscount;
        }

        return (int) round($orderDiscount * $currentItemsTotalToman / $originalItemsTotalToman);
    }
}

<?php

namespace App\Services\Torob;

use Carbon\Carbon;
use App\Modules\Order\Entities\Order;

class TorobOrderService
{
    public static function listOrders($purchaseTimestampGt, $limit)
    {
        $query = Order::query()
            ->whereNotNull('torob_clid')
            ->with(['items.product', 'items.product_variant', 'location'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if (!empty($purchaseTimestampGt)) {
            $query->where('created_at', '>', Carbon::parse($purchaseTimestampGt)->utc());
        }

        $orders = $query->get();

        $data = [];
        foreach ($orders as $order) {
            $data[] = self::formatOrder($order);
        }

        return $data;
    }

    private static function formatOrder($order)
    {
        $phoneNumber = @$order->location->receiptor_mobile;
        if (!$phoneNumber && !empty($order->address)) {
            $address = json_decode($order->address, true);
            $phoneNumber = @$address['receiptor_mobile'];
        }

        $products = [];
        foreach ($order->items as $item) {
            $productUrl = $item->product_variant_id
                ? \App\Library\SiteUrl::product(@$item->product, true, ['variant' => $item->product_variant_id])
                : \App\Library\SiteUrl::product(@$item->product);

            $unitPrice = intval($item->discounted_price) != 0 ? $item->discounted_price : $item->price;

            $products[] = [
                'product_url' => $productUrl,
                'product_price' => intval(str_replace(',', '', (string) $unitPrice)),
                'quantity' => intval(@$item->quantity),
            ];
        }

        return [
            'purchase_timestamp' => $order->created_at->copy()->utc()->format('Y-m-d\TH:i:s\Z'),
            'last_updated_timestamp' => $order->updated_at->copy()->utc()->format('Y-m-d\TH:i:s\Z'),
            'torob_clid' => $order->torob_clid,
            'status' => $order->order_status == 'cancelled' ? 'cancelled' : 'completed',
            'order_value' => intval(str_replace(',', '', (string) @$order->total_price)),
            'shipping_amount' => intval(str_replace(',', '', (string) @$order->shipping_price)),
            'phone_number' => $phoneNumber,
            'products' => $products,
        ];
    }
}

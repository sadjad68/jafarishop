<?php

namespace App\Services\EcommerceTracking;

use Illuminate\Support\Collection;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\OrderItem;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class EcommerceItemMapper
{
    public const CURRENCY = 'TRY';

    public const MAX_OPTION_PAIRS = 3;

    public static function toRial(int $toman): int
    {
        return $toman;
    }

    /**
     * @param  array<string, mixed>  $line  Basket API line or similar array
     */
    public static function fromBasketLine(array $line): array
    {
        $product_id = (int) ($line['product_id'] ?? 0);
        $variant_id = ! empty($line['variant_id']) ? (int) $line['variant_id'] : null;
        $quantity = (int) ($line['quantity'] ?? 1);
        $final_price_toman = (int) ($line['final_price_toman'] ?? 0);
        $brand_title = $line['brand_title'] ?? null;
        $category_titles = $line['category_titles'] ?? [];
        $specifications = $line['specifications'] ?? [];

        return self::buildItem(
            product_id: $product_id,
            variant_id: $variant_id,
            item_name: (string) ($line['product_title'] ?? ''),
            final_price_toman: $final_price_toman,
            quantity: $quantity,
            brand_title: $brand_title,
            category_titles: is_array($category_titles) ? $category_titles : [],
            specifications: $specifications,
        );
    }

    public static function fromOrderItem(OrderItem $order_item): array
    {
        $order_item->loadMissing([
            'product.categories',
            'product.brand',
            'product_variant.specifications.parent',
        ]);

        $product = $order_item->product;
        $variant = $order_item->product_variant_id ? $order_item->product_variant : null;
        $unit_toman = (int) $order_item->discounted_price !== 0
            ? (int) $order_item->discounted_price
            : (int) $order_item->price;

        $category_titles = $product?->categories
            ? $product->categories->pluck('title')->filter()->values()->take(2)->all()
            : [];

        $specifications = $variant?->specifications ?? collect();

        return self::buildItem(
            product_id: (int) $order_item->product_id,
            variant_id: $order_item->product_variant_id ? (int) $order_item->product_variant_id : null,
            item_name: (string) ($product?->title ?? ''),
            final_price_toman: $unit_toman,
            quantity: (int) $order_item->quantity,
            brand_title: $product?->brand?->title,
            category_titles: $category_titles,
            specifications: $specifications,
        );
    }

    /**
     * @param  Product  $product
     * @param  array<string, mixed>  $context  Optional: brand_title, category_titles, specifications
     */
    public static function fromProduct(
        Product $product,
        ?ProductVariant $variant = null,
        int $quantity = 1,
        array $context = []
    ): array {
        $product->loadMissing(['categories', 'brand']);
        if ($variant) {
            $variant->loadMissing(['specifications.parent']);
        }

        $final_price_toman = $variant
            ? (int) ($variant->final_price ?? 0)
            : (int) ($product->final_price ?? 0);

        $category_titles = $context['category_titles'] ?? (
            $product->categories
                ? $product->categories->pluck('title')->filter()->values()->take(2)->all()
                : []
        );

        $specifications = $context['specifications'] ?? ($variant?->specifications ?? []);

        return self::buildItem(
            product_id: (int) $product->id,
            variant_id: $variant ? (int) $variant->id : null,
            item_name: (string) $product->title,
            final_price_toman: $final_price_toman,
            quantity: $quantity,
            brand_title: $context['brand_title'] ?? $product->brand?->title,
            category_titles: $category_titles,
            specifications: $specifications,
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    public static function sumItemsValueRial(array $items): int
    {
        $sum = 0;
        foreach ($items as $item) {
            $price = (int) ($item['price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 1);
            $sum += $price * $qty;
        }

        return floatval($sum);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, mixed>
     */
    public static function eventPayload(array $items, array $extra = []): array
    {
        return array_merge([
            'currency' => self::CURRENCY,
            'value' => self::sumItemsValueRial($items),
            'items' => $items,
        ], $extra);
    }

    public static function buildPurchasePayload(Order $order): array
    {
        $order->loadMissing([
            'items.product.categories',
            'items.product.brand',
            'items.product_variant.specifications.parent',
            'discount',
            'bank',
            'shipping_method',
        ]);

        $items = [];
        foreach ($order->items as $order_item) {
            $items[] = self::fromOrderItem($order_item);
        }

        $cart_value_rial = self::sumItemsValueRial($items);
        $shipping_toman = (int) ($order->shipping_price ?? 0);
        $tax_toman = (int) ($order->tax_price ?? 0);

        $payload = [
            'transaction_id' => (string) $order->id,
            'currency' => self::CURRENCY,
            'value' => $cart_value_rial,
            'tax' => self::toRial($tax_toman),
            'shipping' => self::toRial($shipping_toman),
            'items' => $items,
        ];

        if ($order->discount?->title) {
            $payload['coupon'] = $order->discount->title;
        }
        if ($order->bank?->title) {
            $payload['payment_type'] = $order->bank->title;
        }
        if ($order->shipping_method?->title) {
            $payload['shipping_tier'] = $order->shipping_method->title;
        }

        return $payload;
    }

    /**
     * @param  mixed  $specifications
     */
    private static function buildItem(
        int $product_id,
        ?int $variant_id,
        string $item_name,
        int $final_price_toman,
        int $quantity,
        ?string $brand_title,
        array $category_titles,
        mixed $specifications,
    ): array {
        $item = [
            'item_id' => $variant_id ? (string) $variant_id : (string) $product_id,
            'item_name' => $item_name,
            'price' => self::toRial($final_price_toman),
            'quantity' => max(1, $quantity),
        ];

        if ($variant_id) {
            $item['parent_item_id'] = (string) $product_id;
            $item['variant_id'] = (string) $variant_id;
        }

        if ($brand_title) {
            $item['item_brand'] = $brand_title;
        }

        if (! empty($category_titles[0])) {
            $item['item_category'] = $category_titles[0];
        }
        if (! empty($category_titles[1])) {
            $item['item_category2'] = $category_titles[1];
        }

        $option_data = self::mapSpecifications($specifications);
        return array_merge($item, $option_data);
    }

    /**
     * @param  mixed  $specifications
     * @return array<string, string>
     */
    private static function mapSpecifications(mixed $specifications): array
    {
        $pairs = self::specificationPairs($specifications);
        if ($pairs->isEmpty()) {
            return [];
        }

        $variant_parts = [];
        $option_parts = [];
        $result = [];

        foreach ($pairs->take(self::MAX_OPTION_PAIRS) as $index => $pair) {
            $name = $pair['name'];
            $value = $pair['value'];
            $n = $index + 1;
            $result["option_{$n}_name"] = $name;
            $result["option_{$n}_value"] = $value;
            $variant_parts[] = "{$name}: {$value}";
            $option_parts[] = "{$name}={$value}";
        }

        $result['item_variant'] = implode(' | ', $variant_parts);
        $result['variant_options'] = implode('|', $option_parts);

        return $result;
    }

    /**
     * @return Collection<int, array{name: string, value: string}>
     */
    private static function specificationPairs(mixed $specifications): Collection
    {
        return collect($specifications)->map(function ($spec) {
            if (is_array($spec)) {
                $parent = $spec['parent'] ?? null;
                $name = is_array($parent) ? ($parent['title'] ?? '') : '';
                $value = (string) ($spec['title'] ?? '');

                return $name !== '' && $value !== '' ? ['name' => $name, 'value' => $value] : null;
            }
            if (is_object($spec)) {
                $name = (string) ($spec->parent->title ?? '');
                $value = (string) ($spec->title ?? '');

                return $name !== '' && $value !== '' ? ['name' => $name, 'value' => $value] : null;
            }

            return null;
        })->filter()->values();
    }
}

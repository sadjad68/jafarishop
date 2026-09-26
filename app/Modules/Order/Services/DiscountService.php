<?php

namespace App\Modules\Order\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Modules\Order\DTO\DiscountDTO;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Entities\Discount;
use App\Modules\Order\Entities\OrderShippingStatus;
use App\Modules\Order\Library\ChaparShipment;
use App\Modules\Setting\Entities\Setting;

class DiscountService
{
    public function create(DiscountDTO $discountDTO)
    {
        $discount = Discount::create([
            'title' => $discountDTO->getTitle(),
            'max_usage_per_user' => $discountDTO->getMaxUsagePerUser(),
            'basket_minimum_price' => $discountDTO->getBasketMinimumPrice(),
            'count' => $discountDTO->getCount(),
            'amount' => $discountDTO->getAmount(),
            'type' => $discountDTO->getType(),
            'user_id' => $discountDTO->getUserId(),
            'first_purchase' => $discountDTO->getFirstPurchase(),
            'with_discount' => $discountDTO->getWithDiscount(),
            'pay_type' => $discountDTO->getPayType(),
        ]);
        if($discount->pay_type == 'category') {
            $discount->productCategories()->sync($discountDTO->getProductCategories());
        }elseif($discount->pay_type == 'brand') {
            $discount->brands()->sync($discountDTO->getBrands());
        }
    }

    public function update(int $id, DiscountDTO $discountDTO)
    {
        $discount = Discount::findOrfail($id);
        $discount->update([
            'title' => $discountDTO->getTitle(),
            'max_usage_per_user' => $discountDTO->getMaxUsagePerUser(),
            'basket_minimum_price' => $discountDTO->getBasketMinimumPrice(),
            'count' => $discountDTO->getCount(),
            'amount' => $discountDTO->getAmount(),
            'type' => $discountDTO->getType(),
            'user_id' => $discountDTO->getUserId(),
            'first_purchase' => $discountDTO->getFirstPurchase(),
            'with_discount' => $discountDTO->getWithDiscount(),
            'pay_type' => $discountDTO->getPayType(),
        ]);
        if($discount->pay_type == 'category') {
            $discount->productCategories()->sync($discountDTO->getProductCategories());
            $discount->brands()->detach($discountDTO->getBrands());
        }elseif($discount->pay_type == 'brand') {
            $discount->brands()->sync($discountDTO->getBrands());
            $discount->productCategories()->detach($discountDTO->getProductCategories());
        }else{
            $discount->brands()->detach($discountDTO->getBrands());
            $discount->productCategories()->detach($discountDTO->getProductCategories());
        }
    }

    public function deleteOne(int $id): void
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
    }

    public static function findAll($query = [], $except_id = null, $limit = null)
    {
        $statuses = OrderShippingStatus::query();
        if ($except_id) {
            $statuses->where('id', '<>', $except_id);
        }

        if (isset($query['title'])) {
            $statuses->where('title', 'LIKE', '%' . $query['title'] . '%');
        }
        if ($limit != null) {
            return $statuses->orderby('id', 'DESC')->take($limit)->get();
        } else {
            return $statuses->orderby('id', 'DESC')->get();
        }
    }

    public static function checkDiscount(?string $discount_code)
    {
        if ($discount_code == null) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'کد تخفیف را وارد نمایید.',
            ], 200);
        }

        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
        if (!$basket) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'سبدی برای کاربر یافت نشد.',
            ], 200);
        }

        $discount = self::findDiscount($discount_code);
        if (! $discount) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'کد تخفیف نامعتبر است.',
            ], 200);
        }
        return self::checkDiscountValidity($basket, $discount);
    }

    protected static function findDiscount(string $code): ?Discount
    {
        return Discount::where('title', $code)->latest('id')->first();
    }

    public static function checkDiscountValidity($basket, $discount)
    {
        $user = Auth::user();
        if (!$basket) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'سبد خرید نامعتبر است.',
            ], 200);
        }

        if ($basket->discount_id != null) {
            return response()->json([
                'success' => false,
                'button' => false,
                'sign' => 'error',
                'message' => 'شما قبلا برای این سبد کد تخفیف استفاده کردید',
            ], 200);
        }
        if ($discount->user_id != null && $discount->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'این کد تخفیف متعلق به شما نیست.',
            ], 200);
        }
        if ($discount->count != null && (count($discount->orders) >= $discount->count)) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'تعداد دفعات استفاده از این کد تخفیف به پایان رسیده است',
            ], 200);
        }
        if ($discount->max_usage_per_user != null && ($user->ordersWithDiscount($discount->id)->count() >= $discount->max_usage_per_user)) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'تعداد دفعات استفاده از این کد تخفیف برای شما به پایان رسیده است',
            ], 200);
        }
        if ($discount->first_purchase == 1 && (count($user->orders) != 0)) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'این کد تخفیف مخصوص خرید اول می باشد',
            ], 200);
        }
        if($discount->pay_type == 'category') {
            $hasDiscountableProduct = self::basketHasDiscountableProduct($basket, $discount);
            if (!$hasDiscountableProduct) {
                return response()->json([
                    'success' => false,
                    'button' => false,
                    'message' => 'هیچ محصولی از دسته‌های مشمول تخفیف در سبد شما وجود ندارد.',
                ], 200);
            }
        }
        if($discount->pay_type == 'brand') {
            $hasDiscountableBrandProduct = self::basketHasDiscountableBrandProduct($basket, $discount);
            if (!$hasDiscountableBrandProduct) {
                return response()->json([
                    'success' => false,
                    'button' => false,
                    'message' => 'هیچ محصولی از برند‌های مشمول تخفیف در سبد شما وجود ندارد.',
                ], 200);
            }
        }
        return self::applyDiscountToBasket($basket, $discount, $discount->pay_type);
    }

    public static function applyDiscountToBasket($basket, $discount, $apply_type)
    {
        $items = $basket->items;

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'کد تخفیف مورد نظر قابل اعمال برای این محصولات نمی‌باشد',
            ], 200);
        }

        $discountCategories = $discount->productCategories?->pluck('id')->toArray() ?? [];
        $discountBrands = $discount->brands?->pluck('id')->toArray() ?? [];

        $discountableItems = $items->filter(function ($item) use ($discount, $discountCategories, $discountBrands, $apply_type) {
            $product = $item->product;

            // ✅ فقط قوانین عمومی تخفیف
            if ($discount->with_discount === 0) {
                if (intval($product->discounted_price) != 0 && $product->discounted_price < $product->price) {
                    return false;
                }
            }

            if ($discount->with_discount === 1) {
                if ( $product->discounted_price >= $product->price) {
                    return false;
                }
            }

            // ✅ نوع اعمال تخفیف
            if ($apply_type === 'category') {
                $productCategories = $product->categories->pluck('id')->toArray();
                return !empty($discountCategories) &&
                    count(array_intersect($productCategories, $discountCategories)) > 0;
            }

            if ($apply_type === 'brand') {
                $brandId = $product->brand?->id;
                return !empty($discountBrands) && in_array($brandId, $discountBrands);
            }

            // ✅ product → بدون category و brand
            if ($apply_type === 'product') {
                return true;
            }

            return false;
        });

        if ($discountableItems->isEmpty()) {
            $message = match ($apply_type) {
                'category' => 'این کد تخفیف فقط برای محصولات دسته‌بندی مشخص قابل استفاده است.',
                'brand' => 'این کد تخفیف فقط برای برندهای مشخص قابل استفاده است.',
                'product' => 'این کد تخفیف برای هیچ‌کدام از محصولات سبد خرید قابل استفاده نیست.',
                default => 'این کد تخفیف قابل استفاده نیست.',
            };

            return response()->json([
                'success' => false,
                'button' => false,
                'message' => $message,
            ], 200);
        }

        $sumApplicable = 0;

        foreach ($discountableItems as $item) {
            $price = $item->product_variant_id
                ? $item->productVariant->final_price
                : $item->product->final_price;

            $sumApplicable += $price * $item->quantity;
        }

        if ($discount->basket_minimum_price != 0 && $sumApplicable < $discount->basket_minimum_price) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'برای استفاده از این کد تخفیف، مجموع محصولات مشمول تخفیف باید حداقل ' .
                    number_format($discount->basket_minimum_price) . ' تومان باشد.',
            ], 200);
        }

        $basket->update([
            'discount_id' => $discount->id,
            'discount_apply_type' => $apply_type,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف با موفقیت اعمال شد',
            'discount_id' => $discount->id,
            'applied_items_count' => $discountableItems->count(),
        ], 200);
    }


    private static function basketHasDiscountableProduct($basket, $discount)
    {
        $discountCategories = $discount->productCategories->pluck('id')->toArray();
        foreach ($basket->items as $item) {
            $product = $item->product;
            $productCategories = $product->categories->pluck('id')->toArray();
            $inCategory = empty($discountCategories) || count(array_intersect($productCategories, $discountCategories)) > 0;
            if ($inCategory) {
                if ($discount->with_discount == 1) {
                    return true;
                } elseif ($discount->with_discount == 0 && ($product->discounted_price == null)) {
                    return true;
                } elseif ($discount->with_discount == null) {
                    return true;
                }
            }
        }

        return false;
    }
    private static function basketHasDiscountableBrandProduct($basket, $discount)
    {
        $discountBrands = $discount->brands->pluck('id')->toArray();
        foreach ($basket->items as $item) {
            $product = $item->product;
            $brandId = $product->brand?->id;
            $inBrand = empty($discountBrands) || in_array($brandId, $discountBrands);
            if ($inBrand) {
                if ($discount->with_discount == 1) {
                    return true;
                } elseif ($discount->with_discount == 0 && $product->discounted_price == null || $product->discounted_price == 0) {
                    return true;
                } elseif ($discount->with_discount == null) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function listPrice(?int $bankId = null)
    {
        $basket = Basket::authUser()->latest()->first();
        if (!$basket) {
            return [
                'final_price_sum' => 0,
                'price_sum' => 0,
                'price_discount' => 0,
                'discount_amount' => 0,
                'price_shipping' => 0,
                'freight_balance' => 0,
                'price_cart' => 0,
                'tax_value' => 0,
                'tax' => 0,
                'gateway_tariff' => 0,
                'gateway_tariff_price' => 0,
                'base_final_price_sum' => 0,
            ];
        }

        if ($bankId === null && $basket->bank_id) {
            $bankId = $basket->bank_id;
        }

        $gatewayTariff = self::getGatewayTariff($bankId);

        $discount = $basket->discount;
        $discountCategories = $discount?->productCategories?->pluck('id')->toArray() ?? [];
        $discountBrands = $discount?->brands?->pluck('id')->toArray() ?? [];
        $totalWithoutDiscount = 0;
        $totalDiscounted = 0;
        $discountAmountTotal = 0;
        $weight_sum = 0;
        foreach ($basket->items as $item) {
            $qty = $item->quantity;
            $product = $item->product;
            $variant = $item->product_variant_id ? $item->productVariant : null;
            $price = $variant ? $variant->price : $product->price;
            $finalPrice = $variant ? $variant->final_price : $product->final_price;
            $totalWithoutDiscount += $price * $qty;
            $isDiscountable = false;
            if ($discount) {
                $pay_type = $discount->pay_type;
                if ($pay_type === 'category') {
                    $productCategories = $product->categories->pluck('id')->toArray();
                    $isDiscountable = !empty($discountCategories) && count(array_intersect($productCategories, $discountCategories)) > 0;
                }
                elseif ($pay_type === 'brand') {
                    $brandId = $product->brand?->id;
                    $isDiscountable = !empty($discountBrands) && in_array($brandId, $discountBrands);
                }
                elseif ($pay_type === 'product') {
                    $isDiscountable = $basket->items->where('product_id', $product->id)->isNotEmpty();
                }
            }


            $passesWithDiscount = true;

            if ($discount && $isDiscountable) {
                if ($discount->with_discount === 0) {
                    $passesWithDiscount = intval($product->discounted_price) === 0;
                } elseif ($discount->with_discount === 1) {
                    $passesWithDiscount = intval($product->discounted_price) !== 0 || intval($product->price) !== 0;
                }
            }

            if ($discount && $isDiscountable && $passesWithDiscount) {
                if ($discount->type === 'percent') {

                    $discountedPrice = $finalPrice - ($finalPrice * $discount->amount / 100);
                } elseif ($discount->type === 'cash') {
                    $discountedPrice = max(0, $finalPrice - $discount->amount);
                } else {
                    $discountedPrice = $finalPrice;
                }

                $totalDiscounted += $discountedPrice * $qty;
                $discountAmountTotal += ($finalPrice - $discountedPrice) * $qty;

            } else {
                $totalDiscounted += $finalPrice * $qty;
            }

            $weight = $variant ? $variant->weight : $product->weight;
            $weight_sum += $weight * $qty;
        }

        if ($basket->shippingMethod && $basket->shippingMethod->type === 'with_weight') {
            $weight_sum = ceil($weight_sum / 100) * 100;
        }

        $shippingData = self::shipmentMethodPrice(
            $basket,
            $weight_sum,
            $totalDiscounted
        );

        $tax = (int)Setting::where('key', 'tax')->value('value');
        $tax_value = ($totalDiscounted * $tax) / 100;
        $shippingPrice = $shippingData['price'];
        $priceCartBeforeTariff = $totalDiscounted + $tax_value + (is_numeric($shippingPrice) ? (float) $shippingPrice : 0);

        $gatewayTariffPrice = 0;
        $priceCart = $priceCartBeforeTariff;
        $displayPriceSum = $totalWithoutDiscount;
        $displayFinalPriceSum = $totalDiscounted;
        if ($gatewayTariff > 0) {
            $priceCart = self::applyGatewayTariff($priceCartBeforeTariff, $gatewayTariff);
            $gatewayTariffPrice = $priceCart - (int) round($priceCartBeforeTariff);
            $displayPriceSum = self::applyGatewayTariff($totalWithoutDiscount, $gatewayTariff);
            $displayFinalPriceSum = self::applyGatewayTariff($totalDiscounted, $gatewayTariff);
        }

        return [
            'final_price_sum' => $displayFinalPriceSum,
            'price_sum' => $displayPriceSum,
            'base_final_price_sum' => $totalDiscounted,
            'price_discount' => $discountAmountTotal,
            'discount_amount' => $discountAmountTotal,
            'price_shipping' => $shippingPrice,
            'freight_balance' => $shippingData['freight_balance'],
            'price_cart' => $priceCart,
            'tax_value' => $tax_value,
            'tax' => $tax,
            'gateway_tariff' => $gatewayTariff,
            'gateway_tariff_price' => (int) $gatewayTariffPrice,
        ];
    }

    public static function getGatewayTariff(?int $bankId): float
    {
        if (!$bankId) {
            return 0;
        }

        return floatval(Bank::find($bankId)?->gateway_tariff ?? 0);
    }

    public static function applyGatewayTariff(float $amount, float $tariff): int
    {
        if ($tariff <= 0) {
            return (int) round($amount);
        }

        return (int) round($amount * (1 + ($tariff / 100)));
    }


    public static function shipmentMethodPrice($basket, $weight_sum, $final_price_sum)
    {
        $price = "";
        $freight_balance = 0;
        if (!$basket->shippingMethod) {
            return ['price' => $price, 'freight_balance' => $freight_balance];
        } else {
            if ($basket->shippingMethod->type == "fixed_price") {
                $price = @$basket->shippingMethod->price;
            } elseif ($basket->shippingMethod->type == "with_weight") {
                $price = ($weight_sum / 100) * $basket->shippingMethod->price;
            } else {
                $response = ChaparShipment::quote([
                    'origin' => @$basket->shippingMethod->sender_city->chapar_id,
                    'destination' => @$basket->address->city->chapar_id,
                    'weight' => $weight_sum / 1000,
                    'value' => $final_price_sum . '0',
                    'method' => $basket->shippingMethod->chapar_type,
                ]);
                if ($response['result'] === true) {
                    $price = ($response['objects']['order']['price']['fld_Total_Cost']) / 10 ?? null;
                } else {
                    throw new \InvalidArgumentException($response['message']);
                }
            }
            if ($basket->shippingMethod->freight_balance == 1) {
                $price = 0;
                $freight_balance = 1;
            }
            if ($basket->shippingMethod->price_ceiling != 0 && $final_price_sum > $basket->shippingMethod->price_ceiling) {
                $price = 0;
            }
        }

        return ['price' => $price, 'freight_balance' => $freight_balance];
    }
}

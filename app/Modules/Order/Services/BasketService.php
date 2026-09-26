<?php

namespace App\Modules\Order\Services;

use App\Services\Torob\TorobAttributionService;
use Illuminate\Support\Facades\Auth;
use App\Modules\Order\Entities\Basket;
use App\Modules\Order\Entities\BasketItem;
use App\Modules\Order\Library\ChaparShipment;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;


class BasketService
{
    //addToBasket
    public static function create($product_id, $product_variant_id, $quantity, $cart = false)
    {
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
        if (!$basket) {
            $basket = Basket::create([
                'user_id' => @\Auth::id()
                , 'user_cookie' => session()->get('custom_data'),
            ]);
            TorobAttributionService::syncBasket();
        }
        return self::checkValidity($product_id, $product_variant_id, $quantity, $basket, $cart);

    }

    public static function checkValidity($product_id, $product_variant_id, $quantity, $basket, $cart)
    {
        $product = Product::find($product_id);
        if ($product->stock == 0) {
            return response()->json([
                'success' => false,
                'button' => false,
                'message' => 'محصول مورد نظر موجود نمی باشد.',
            ], 200);
        }
        $item = null;
        if (count($product->variants) > 0) {

            if (@$product_variant_id == null) {
                return response()->json([
                    'success' => false,
                    'button' => false,
                    'swal' => true,
                    'message' => 'لطفا محصول مورد نظر خود را انتخاب کنید ' ,
                ], 200);
            }
            $item = BasketItem::whereBasketId($basket->id)->whereProductId($product->id)->where('product_variant_id', $product_variant_id)->first();
            $variant = ProductVariant::find($product_variant_id);
            $stock = $variant ? $variant->stock : 0;
        } else {
            $item = BasketItem::whereBasketId($basket->id)->whereProductId($product->id)->first();
            $stock = $product ? $product->stock : 0;
        }
        $item_quantity = $item ? intval($item->quantity) : 0;

        if (intval($quantity) > 0) {

            if (intval($product->final_price) != 0) {
                if ($cart) {
                    $main_quantity = intval($quantity);
                } else {
                    $main_quantity = intval($quantity) + $item_quantity;
                }

                if ($stock >= $main_quantity) {
                    self::addItems($product_id, $product_variant_id, $main_quantity, $basket);


                } else {
                    return response()->json([
                        'success' => false,
                        'button' => false,
                        'swal' => true,
                        'message' => 'موجودی انبار کافی نیست',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'button' => false,
                    'message' => 'محصول فاقد قیمت میباشد',
                ], 200);
            }
        }


    }

    public static function addItems($product_id, $product_variant_id, $quantity, $basket)
    {
        BasketItem::updateOrCreate(
            [
                'product_id' => $product_id,
                'product_variant_id' => $product_variant_id,
                'basket_id' => $basket->id,
            ],
            [
                'quantity' => $quantity,
            ]
        );
        return response()->json([
            'message' => 'محصول با موفقیت به سبد خرید اضافه شد.',
        ], 200);

    }

    //Cart
    public static function findBasketAndCheckStock()
    {
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
        if ($basket) {
            foreach ($basket->items as $item) {
                if (@$item->product == null){
                    $item->delete();
                }
                if ($item->product_variant_id != null) {
                    if ($item->productVariant == null || $item->productVariant->stock == 0 || ($item->quantity > $item->productVariant->stock)) {
                        $item->delete();
                    }
                    if ($item->productVariant == null || $item->productVariant->final_price == 0) {
                        $item->delete();
                    }
                } else {
                    if ($item->product == null ||$item->product->stock == 0 || ($item->quantity > $item->product->stock)) {

                        $item->delete();
                    }
                    if ($item->product == null ||$item->product->final_price == 0 ) {

                        $item->delete();
                    }
                }
                if (!isset($basket->discount)){
                    $basket->update([
                        'discount_id' => null
                    ]);
                }

                $basket->save();
            }
            if (count(@$basket->items) == 0){
                $basket->delete();
            }
        }

        return $basket;
    }

    //delete
    public static function delete()
    {
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
        if ($basket) {
            $basket->items()->delete();
            $basket->delete();
        }
    }

    public static function removeItem($itemId)
    {
        BasketItem::destroy($itemId);
    }

    public static function listPrice()
    {
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
        $final_price_sum = 0;
        $price_sum = 0;
        if ($basket && count($basket->items) > 0) {
            foreach ($basket->items as $item) {
                $final_price = $item->product_variant_id ? $item->productVariant->final_price : $item->product->final_price;
                $final_price_quantity = $final_price * $item->quantity;
                $price = @$item->productVariant ? $item->productVariant->price : $item->product->price;
                $price_quantity = $price * $item->quantity;
                $final_price_sum += $final_price_quantity;
                $price_sum += $price_quantity;
            }
        }

        $price_discount = $price_sum - $final_price_sum;
        return ['final_price_sum' => $final_price_sum, 'price_sum' => $price_sum, 'price_discount' => $price_discount];
    }

    public static function addressPrice()
    {
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
        $final_price_sum = 0;
        $price_sum = 0;
        $weight_sum = 0;
        foreach ($basket->items as $item) {
            $final_price = $item->product_variant_id ? $item->productVariant->final_price : $item->product->final_price;
            $final_price_quantity = $final_price * $item->quantity;
            $price = $item->product_variant_id ?
                (intval($item->productVariant->discounted_price) != 0 ? $item->productVariant->price : 0) :
                (intval($item->product->discounted_price) != 0 ? $item->product->price : 0);
            $price_quantity = $price * $item->quantity;
            $final_price_sum += $final_price_quantity;
            $price_sum += $price_quantity;
            $weight = $item->product_variant_id ? $item->productVariant->weight : $item->product->weight;
            $weight_quantity = $weight * $item->quantity;
            $weight_sum += $weight_quantity;
            if (@$basket->shippingMethod->type == "with_weight") {
                $weight_sum = ceil($weight_sum / 100) * 100;
            }
        }
        $price_discount = $price_sum - $final_price_sum;
        $address = @$basket->address;
        $price_shipping = self::shipmentMethodPrice($basket->shippingMethod,$weight_sum,$final_price_sum,$address)['price'];
        $freight_balance = self::shipmentMethodPrice($basket->shippingMethod,$weight_sum,$final_price_sum,$address)['freight_balance'];
//        $price_shipping = $basket->shippingMethod ? intval($basket->shippingMethod->price) : "";
        $price_cart = (!empty($price_shipping) && is_numeric($price_shipping))  ? intval($final_price_sum) + $price_shipping : $final_price_sum;
        return ['final_price_sum' => $final_price_sum, 'price_sum' => $price_sum, 'price_discount' => $price_discount,
            'price_shipping' => $price_shipping, 'price_cart' => $price_cart,'freight_balance'=>$freight_balance];
    }
    public static function calculateShipping(Basket $basket)
{
    if (!$basket || !$basket->shippingMethod) {
        return 0;
    }

    $weight_sum = 0;
    foreach ($basket->items as $item) {
        $weight = $item->product_variant_id ? $item->productVariant->weight : $item->product->weight;
        $weight_sum += $weight * $item->quantity;
    }

    if ($basket->shippingMethod->type === "with_weight") {
        $weight_sum = ceil($weight_sum / 100) * 100;
    }

    $shipment = self::shipmentMethodPrice($basket->shippingMethod, $weight_sum, 0, $basket->address);
    return $shipment['price'] ?? 0;
}

    public static function shipmentMethodPrice($shippingMethod,$weight_sum,$final_price_sum,$address){
        $price = "";
        $freight_balance = 0;
        if (!$shippingMethod){
            return ['price' => $price, 'freight_balance' => $freight_balance];
        }

        else{
            if ($shippingMethod->type == "fixed_price"){
                $price = @$shippingMethod->price;
            }
            elseif($shippingMethod->type == "with_weight"){
                //به ازای هر صد گرم قیمت گذاری شده
                $price = ($weight_sum/100) * $shippingMethod->price;
            }
            else{
                //وزن به کیلو
                //قیمت به ریال
                $response = ChaparShipment::quote([
                    'origin' => @$shippingMethod->sender_city->chapar_id,
                    'destination' => @$address->city->chapar_id,
                    'weight' => $weight_sum/1000,
                    'value' => $final_price_sum.'0',
                    'method' => $shippingMethod->chapar_type,

                ]);
                //قیمت به تومان
                if ($response['result'] === true){
                    $price = ($response['objects']['order']['price']['fld_Total_Cost'] ) / 10 ?? null;

                }
                else{
                    throw new \InvalidArgumentException($response['message']);
                }

            }
            if ($shippingMethod->freight_balance == 1){
                $price = 0;
                $freight_balance = 1;
            }
            if ($shippingMethod->price_ceiling != 0 && $final_price_sum > $shippingMethod->price_ceiling){
                $price = 0;
            }
        }



        return ['price'=>$price , 'freight_balance'=>$freight_balance];


    }

    //auth
    public static function findUserBasketAndUpdateBaskets()
    {
        $currentBasket = Basket::cookieUser()->whereNotNull('user_cookie')->whereNull('user_id')->first();
        $currentBaskets = Basket::orderBy('id', 'DESC')->where('user_id', Auth::id())->where('id', '<>', @$currentBasket->id)->get();
        if ($currentBasket) {
            foreach ($currentBaskets as $row) {
                $row->delete();
            }
            $currentBasket->update([
                'user_id' => Auth::id(),
            ]);
            TorobAttributionService::syncBasket();
        }
        return;
    }
//address
public static function checkShipmentPriceBaseOnAddress($address){
    $basket = Basket::authUser()->orderBy('id', 'DESC')->first();
    $customShipments = [];
    foreach (@$address->city->availableShippingMethods as $shipment){
        $weight_sum = 0;
        foreach ($basket->items as $item) {
            $weight = $item->product_variant_id ? $item->productVariant->weight : $item->product->weight;
            $weight_quantity = $weight * $item->quantity;
            $weight_sum += $weight_quantity;
            if ($shipment->type == "with_weight") {
                $weight_sum = ceil($weight_sum / 100) * 100;
            }
        }
        if ($shipment->type == "with_weight" || $shipment->type == "fixed_price") {
            $price = self::shipmentMethodPrice($shipment, $weight_sum, 0, $address)['price'];
        }
        else{
            $price = "not_custom";
        }
        $customShipments[] = [
            'id' => $shipment->id,
            'title' => $shipment->title,
            'type' => $shipment->type,
            'freight_balance' => $shipment->freight_balance,
            'description' => $shipment->description,
            'price' => $price,

        ];
    }
    return $customShipments;
}

}

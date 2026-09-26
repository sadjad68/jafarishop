<?php

namespace App\Modules\Order\Services;

use App\Library\SiteHelper;
use App\Services\Torob\TorobAttributionService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Modules\General\Helper\FileManager;
use App\Modules\General\Helper\Sms;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Entities\InventoryTransaction;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\OrderImage;
use App\Modules\Order\Entities\OrderItem;
use App\Modules\Order\Entities\OrderShippingStatus;
use App\Modules\Order\Entities\ProductStockReservation;
use App\Modules\Order\Library\Sadad;
use App\Modules\Order\Library\Saman;
use App\Modules\Order\Library\SepSaman;
use App\Modules\Order\Library\SnappPay;
use App\Modules\Order\Library\ZarinPal;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Setting\Entities\Setting;


class OrderService
{
    protected $bankPaymentService;

    public function __construct(BankPaymentService $bankPaymentService)
    {
        $this->bankPaymentService = $bankPaymentService;
    }

    public function findByField($field, $value)
    {

        return Order::orderBy('id', 'DESC')->where($field, $value)->first();
    }

    public static function findByAuthority($authority)
    {

        return Order::orderBy('id', 'DESC')->where('transaction_info->post->Authority', $authority)->first();
    }

    public static function findByToken($token)
    {

        return Order::orderBy('id', 'DESC')->where('transaction_info->post->Token', $token)->first();
    }

    public static function findById($id)
    {

        return Order::findOrFail($id);
    }

    public static function byId($id)
    {

        return Order::find($id);
    }

    //addToBasket
    public function create($basket, $user_description = null)
    {
        $currentOrder = Order::orderBy('id', 'DESC')->where('basket_id', $basket['id'])->first();
        if ($currentOrder != null) {
            self::releaseStockReservation($currentOrder, 'released');
            $currentOrder->delete();
        }

        return $this->createOrder($basket, $user_description);
    }

    private function createOrder($currentBasket, $user_description = null)
    {
        $default_shiping_status = OrderShippingStatus::orderBy('default', 'DESC')->first();
        $price_details = $this->priceCalculate();
        $torobClid = TorobAttributionService::getForOrder($currentBasket);
        $order = Order::create([
            'user_id' => $currentBasket['user_id']
            , 'basket_id' => $currentBasket['id']
            , 'address_id' => @$currentBasket->address->id
            , 'city_id' => @$currentBasket->address->city_id
            , 'state_id' => @$currentBasket->address->state_id
            , 'address' => json_encode([
                'state' => @$currentBasket->address->state->name,
                'city' => @$currentBasket->address->city->name,
                'address' => @$currentBasket->address->address,
                'receiptor_mobile' => @$currentBasket->address->receiptor_mobile,
                'postal_code' => @$currentBasket->address->postal_code,
            ])
            , 'receiptor_full_name' => @$currentBasket->address->receiptor_full_name
            , 'shipping_method_id' => @$currentBasket['shipping_method_id']
            , 'bank_id' => @$currentBasket['bank_id']
            , 'order_status' => "paying"
            , 'discount_id' => @$currentBasket['discount_id']
            , 'shipping_price' => $price_details['shipping_price']
            , 'total_price' => $price_details['total_price']
            , 'discount_price' => $price_details['discount_price']
            , 'payment_price' => $price_details['payment_price']
            , 'deposit_price' => $price_details['deposit_price']
            , 'remaining_price' => $price_details['remaining_price']
            , 'current_tax' => $price_details['current_tax']
            , 'tax_price' => $price_details['tax_price']
            , 'gateway_tariff' => $price_details['gateway_tariff']
            , 'gateway_tariff_price' => $price_details['gateway_tariff_price']
            , 'freight_balance' => $price_details['freight_balance']
            , 'user_description' => $user_description
            , 'shipping_status_id' => $default_shiping_status ? $default_shiping_status->id : null
            , 'torob_clid' => $torobClid
        ]);
        $order->save();
        $weight_sum = 0;
        foreach ($currentBasket->items as $item) {
            $weight = $item->product_variant_id ? $item->productVariant->weight : $item->product->weight;
            OrderItem::create([
                'order_id' => $order->id
                , 'product_id' => @$item->product_id
                , 'product_variant_id' => @$item->product_variant_id
                , 'quantity' => @$item->quantity
                , 'weight' => @$weight
                , 'price' => $item->product_variant_id ? $item->productVariant->price : $item->product->price
                , 'discounted_price' => $item->product_variant_id ?
                    (intval($item->productVariant->discounted_price) != 0 ? $item->productVariant->discounted_price : 0) :
                    (intval($item->product->discounted_price) != 0 ? $item->product->discounted_price : 0)
            ]);
            $weight_quantity = $weight * $item->quantity;
            $weight_sum += $weight_quantity;
            if ($currentBasket->shippingMethod->type == "custom") {
                $weight_sum = ceil($weight_sum / 100) * 100;
            }
        }
        $order->total_weight = $weight_sum;
        $order->save();
        return $order;
    }

    //price calculator
    private function priceCalculate()
    {
        $deposit_price = intval(Setting::where('key', 'deposit_price')->first()['value']);
        $cart_deposit = intval(Setting::where('key', 'cart_deposit')->first()['value']);

        $listPrice = DiscountService::listPrice();
        $shipping_price = $listPrice['price_shipping'];
        $total_price = $listPrice['base_final_price_sum'];
        $discount_price = $listPrice['discount_amount'];
        $payment_price = $listPrice['price_cart'];
        $deposit_price = ($cart_deposit != 0 && $payment_price >= $cart_deposit) ? ($deposit_price ? intval($deposit_price) : 0) : 0;
        $remaining_price = $deposit_price != 0 ? $payment_price - $deposit_price : 0;
        $current_tax = $listPrice['tax'];
        $tax_price = $listPrice['tax_value'];
        $freight_balance = $listPrice['freight_balance'];
        $gateway_tariff = $listPrice['gateway_tariff'];
        $gateway_tariff_price = $listPrice['gateway_tariff_price'];
        return [
            'shipping_price' => $shipping_price,
            'total_price' => $total_price,
            'discount_price' => $discount_price,
            'payment_price' => $payment_price,
            'tax_price' => $tax_price,
            'current_tax' => $current_tax,
            'freight_balance' => $freight_balance,
            'deposit_price' => $deposit_price,
            'remaining_price' => $remaining_price,
            'gateway_tariff' => $gateway_tariff,
            'gateway_tariff_price' => $gateway_tariff_price,
        ];
    }

    //checkout
    public function checkout($currentOrder)
    {
        $price = intval(str_replace(',', '', $currentOrder->deposit_price)) != 0 ?
            intval(str_replace(',', '', $currentOrder->deposit_price)) :
            intval(str_replace(',', '', $currentOrder->payment_price));
        if ($currentOrder['discount_id'] != null && $price <= 0) {
            return $this->handleFreeOrder($currentOrder);
        }
        $bank = Bank::findOrfail($currentOrder['bank_id']);
        if ($bank->bank_type === 'cardtocard') {
            return $this->handleCardToCardOrder($currentOrder);
        }
        return $this->bankPaymentService->handlePayment($bank, $currentOrder, $price);
    }

    public static function storeImage(Order $order, UploadedFile $file): void
    {
        $final_name = FileManager::uploadRaw($file, 'order/'. $order->id);
        OrderImage::create([
            'order_id' => $order->id,
            'file' => $final_name,
        ]);
    }

    public static function storeBijakImage(Order $order, UploadedFile $file): void
    {
        $final_name = FileManager::uploadRaw($file, 'order/'. $order->id);
        $oldName = $order->getRawOriginal('bijak_image');
        if ($oldName && $oldName !== $final_name) {
            FileManager::delete('order/' . $order->id . '/' . $oldName);
        }
        $order->update([
            'bijak_image' => $final_name,
        ]);
    }

    public static function reserveStock(Order $order): void
    {
        if (self::stockReservationsForOrder($order)->reserved()->exists()) {
            return;
        }

        DB::transaction(function () use ($order) {
            $order->loadMissing('items', 'bank');
            $expireMinutes = @$order->bank->reservation_expire_minutes;
            $expiresAt = $expireMinutes ? Carbon::now()->addMinutes((int) $expireMinutes) : null;
            foreach ($order->items as $item) {
                self::setStockAndPrice($item);

                ProductStockReservation::create([
                    'order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'status' => 'reserved',
                    'expires_at' => $expiresAt,
                ]);

                InventoryTransaction::create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                    'type' => 'reserve',
                    'description' => 'رزرو موجودی کارت به کارت',
                ]);
            }
        });
    }

    public static function confirmStockReservation(Order $order): void
    {
        $reservations = self::stockReservationsForOrder($order)->reserved()->get();
        if ($reservations->isEmpty()) {
            return;
        }

        foreach ($reservations as $reservation) {
            $reservation->update([
                'status' => 'confirmed',
            ]);
        }
    }

    /**
     * @return array<int, string>
     */
    public static function fulfillStockOnCardToCardConfirm(Order $order): array
    {
        $warnings = [];

        DB::transaction(function () use ($order, &$warnings) {
            $order->loadMissing('items.product', 'items.product_variant');

            foreach ($order->items as $item) {
                $reservation = ProductStockReservation::where('order_item_id', $item->id)
                    ->where('status', 'reserved')
                    ->first();

                if ($reservation) {
                    $reservation->update(['status' => 'confirmed']);
                    continue;
                }

                $available = self::getAvailableStockForItem($item);
                $required = (int) $item->quantity;
                if ($available < $required) {
                    $productTitle = $item->product->title ?? ('محصول #' . $item->product_id);
                    $warnings[] = sprintf(
                        'موجودی محصول «%s» کافی نیست (موجودی: %d، مورد نیاز: %d).',
                        $productTitle,
                        $available,
                        $required
                    );
                }

                self::setStockAndPrice($item);

                InventoryTransaction::create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                    'type' => 'add',
                    'description' => 'کسر موجودی پس از تایید فیش (بدون رزرو فعال)',
                ]);
            }
        });

        return $warnings;
    }

    private static function getAvailableStockForItem($item): int
    {
        if ($item->product_variant_id != null) {
            $variant = ProductVariant::find($item->product_variant_id);

            return (int) ($variant->stock ?? 0);
        }

        $product = Product::find($item->product_id);

        return (int) ($product->stock ?? 0);
    }

    public static function releaseStockReservation(Order $order, string $status = 'released'): void
    {
        $reservations = self::stockReservationsForOrder($order)->reserved()->get();
        if ($reservations->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($reservations, $order, $status) {
            $order->loadMissing('items');
            foreach ($reservations as $reservation) {
                $item = $order->items->firstWhere('id', $reservation->order_item_id);
                if (!$item) {
                    $item = (object) [
                        'product_id' => $reservation->product_id,
                        'product_variant_id' => $reservation->product_variant_id,
                        'quantity' => $reservation->quantity,
                    ];
                }
                self::reverseStockAndPrice($item);

                $reservation->update([
                    'status' => $status,
                ]);

                InventoryTransaction::create([
                    'product_id' => $reservation->product_id,
                    'product_variant_id' => $reservation->product_variant_id,
                    'quantity' => $reservation->quantity,
                    'order_id' => $order->id,
                    'type' => $status === 'expired' ? 'expire_reserve' : 'release_reserve',
                    'description' => $status === 'expired'
                        ? 'انقضای رزرو موجودی کارت به کارت'
                        : 'آزادسازی رزرو موجودی کارت به کارت',
                ]);
            }
        });
    }

    public static function expireStockReservations(): int
    {
        $orderIds = ProductStockReservation::expired()
            ->with('orderItem:id,order_id')
            ->get()
            ->pluck('orderItem.order_id')
            ->filter()
            ->unique();
        $count = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::with('items', 'bank')->find($orderId);
            if (!$order) {
                continue;
            }

            self::releaseStockReservation($order, 'expired');
            $count++;
        }

        return $count;
    }

    private static function stockReservationsForOrder(Order $order)
    {
        return ProductStockReservation::whereHas('orderItem', function ($query) use ($order) {
            $query->where('order_id', $order->id);
        });
    }

    private function handleCardToCardOrder($currentOrder)
    {
        // پرداخت کارت به کارت همیشه کل مبلغ است و شامل جریان بیعانه نمی‌شود
        if (intval(str_replace(',', '', $currentOrder->deposit_price)) != 0) {
            $currentOrder->update([
                'deposit_price' => 0,
                'remaining_price' => 0,
            ]);
        }

        return redirect(route('basket.order-images', ['id' => $currentOrder->id]));
    }

    private function handleFreeOrder($currentOrder)
    {
        $currentOrder->update([
            'order_status' => "paid"
        ]);
        self::inventory($currentOrder);
        if (Auth::user()->mobile) {
            $kavenegar = new Sms();
            $kavenegar->sendLookup(
                "userBuy",
                [
                    "token" => "$currentOrder->id",
                ],
                Auth::user()->mobile
            );
        }

        $admin_mobile = Setting::where('key', 'admin_mobile')->whereNotNull('value')->first();
        if ($admin_mobile) {
            $kavenegar = new Sms();
            $kavenegar->sendLookup(
                "adminBuy",
                [
                    "token" => "$currentOrder->id",

                ],
                $admin_mobile->value
            );
        }

        if (intval(str_replace(',', '', $currentOrder->deposit_price)) != 0) {
            return redirect(route('basket.order-images', ['id' => $currentOrder->id]));
        } else {
            return redirect(route('basket.success', ['id' => $currentOrder->id]));
        }
    }

    public static function inventory($currentOrder)
    {
        foreach ($currentOrder->items as $item) {
            $resids = [
                'product_id' => @$item->product_id,
                'product_variant_id' => @$item->product_variant_id,
                'quantity' => @$item->quantity,
                'order_id' => $item->order_id,
                'type' => "add",
            ];
            self::setStockAndPrice($item);
        }
        InventoryTransaction::insert($resids);
    }

    public static function setStockAndPrice($item)
    {
        $product = Product::find(@$item->product_id);

        $product_variant_check = null;
        if ($item->product_variant_id != null) {

            $product_variant_check = ProductVariant::find($item->product_variant_id);
            $product_variant_check->update([
                'stock' => intval(@$product_variant_check->stock) - intval(@$item->quantity),
            ]);
            $product_variant_check->save();
            $sum_stock = $product->variants()->orderBy('final_price', 'ASC')->sum('stock');
            //Review : اگر یک محصول با متغییر فقط یک موجودی داشته باشه این خط خطا میداد
            $minimum_price_variant = $product->variants()->orderBy('final_price', 'ASC')->where('stock', '<>', '0')->first();
            if ($minimum_price_variant == null) {
                $minimum_price_variant = $product->variants()->orderBy('final_price', 'ASC')->first();
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
                'stock' => intval(@$product->stock) - intval(@$item->quantity),
            ]);
        }
        $product->save();
    }

    public static function reverseStockAndPrice($item)
    {
        $product = Product::find(@$item->product_id);

        $product_variant_check = null;
        if ($item->product_variant_id != null) {

            $product_variant_check = ProductVariant::find($item->product_variant_id);
            $product_variant_check->update([
                'stock' => intval(@$product_variant_check->stock) + intval(@$item->quantity),
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
                'stock' => intval(@$product->stock) + intval(@$item->quantity),
            ]);
        }
        $product->save();
    }

    public function callback(string $field, string $value, array $data)
    {
        $order = $this->findByField($field, $value);
        if ($order == null) {
            return redirect(route('basket.cart'))->with('error', 'فاکتور معتبر نمی باشد');
        }
        if($order->order_status == "deposit_paid" ||  $order->order_status == "paid"){
            if (intval(str_replace(',', '', $order->deposit_price)) != 0) {
                return redirect(route('basket.order-images', ['id' => $order->id]));
            } else {
                return redirect(route('basket.success', ['id' => $order->id]));
            }
        }
        $price = intval(str_replace(',', '', $order->deposit_price)) != 0 ?
            intval(str_replace(',', '', $order->deposit_price)) :
            intval(str_replace(',', '', $order->payment_price));
        $is_success_payment = $this->bankPaymentService->handleVerify($order, $data, $price);
        if ($is_success_payment) {
            return $this->handleSuccessOrderPayment($order);
        } else {
            return $this->handleFailedOrder();
        }
    }

    private function handleFailedOrder()
    {
        return redirect(route('basket.failed'))->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
    }

    public function handleSuccessOrderPayment($order){
        if ($order->order_status === "paid") {
                return redirect(route('basket.success', ['id' => $order->id]));
        }
        $order->update([
            'order_status' => intval($order->deposit_price) != 0 ? "deposit_paid" : "paid"
        ]);
        self::inventory($order);
        if (Auth::user()->mobile) {
            $kavenegar = new Sms();
            $kavenegar->sendLookup(
                "userBuy",
                [
                    "token" => "$order->id",
                ],
                Auth::user()->mobile
            );
        }
        $admin_mobile = Setting::where('key', 'admin_mobile')->whereNotNull('value')->first();
        if ($admin_mobile) {
            $kavenegar = new Sms();
            $kavenegar->sendLookup(
                "adminBuy",
                [
                    "token" => "$order->id",

                ],
                $admin_mobile->value
            );
        }

        if (intval(str_replace(',', '', $order->deposit_price)) != 0) {
            return redirect(route('basket.order-images', ['id' => $order->id]));
        } else {
            return redirect(route('basket.success', ['id' => $order->id]));
        }
    }


    public static function finish($currentOrder)
    {
        $price = intval(str_replace(',', '', $currentOrder->deposit_price)) != 0 ?
            intval(str_replace(',', '', $currentOrder->deposit_price)) :
            intval(str_replace(',', '', $currentOrder->payment_price));
        $bank = Bank::findOrfail($currentOrder['bank_id']);

        $myBank = new ZarinPal($bank);
        $authority = json_decode(@$currentOrder->transaction_info, true)['post']['Authority'];
        $check = $myBank->verifyTransaction($authority, $price);
        Auth::loginUsingId($currentOrder->user_id);
        if ($check['status'] == "failed") {
            $currentOrder->delete();
            return redirect(route('basket.failed'))->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
        } else {
            $transaction_info = [
                'post' => [
                    "Authority" => $authority
                ],
                'verify' => [
                    "RefID" => $check["RefID"]
                ]
            ];
            $currentOrder->update([
                'transaction_info' => $transaction_info,
                'order_status' => intval(str_replace(',', '', $currentOrder->deposit_price)) != 0 ? "deposit_paid" : "paid"
            ]);
            if ($check['inventory'] === true) {
                self::inventory($currentOrder);
            }
            if (Auth::user()->mobile) {
                $kavenegar = new Sms();
                $kavenegar->sendLookup(
                    "userBuy",
                    [
                        "token" => "$currentOrder->id",
                    ],
                    Auth::user()->mobile
                );
            }

            $admin_mobile = Setting::where('key', 'admin_mobile')->whereNotNull('value')->first();
            if ($admin_mobile) {
                $kavenegar = new Sms();
                $kavenegar->sendLookup(
                    "adminBuy",
                    [
                        "token" => "$currentOrder->id",

                    ],
                    $admin_mobile->value
                );
            }

            if (intval(str_replace(',', '', $currentOrder->deposit_price)) != 0) {
                return redirect(route('basket.order-images', ['id' => $currentOrder->id]));
            } else {
                return redirect(route('basket.success', ['id' => $currentOrder->id]));
            }


        }

    }

    public static function finishSaman($currentOrder, $stats)
    {
        if (@$stats['state'] == 'CanceledByUser') {

            $transaction_info = [
                'post' => [
                    "RefNum" => @$stats['refNum']
                ],
                'verify' => [
                    "TraceNo" => $stats["traceNo"]
                ]
            ];
            $currentOrder->update([
                'transaction_info' => $transaction_info,
                'order_status' => "cancelled"
            ]);

            $currentOrder->delete();
            return redirect(route('basket.failed'))->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
        }
        Auth::loginUsingId($currentOrder->user_id);
        $bank = Bank::findOrfail($currentOrder['bank_id']);
        $saman_bank = new SepSaman($bank);
        $result = $saman_bank->verifyTransaction(@$stats['refNum']);

        $transaction_info = [
            'post' => [
                "RefNum" => @$stats['refNum']
            ],
            'verify' => [
                "TraceNo" => $stats["traceNo"]
            ]
        ];
        $currentOrder->update([
            'transaction_info' => $transaction_info,

        ]);

        Auth::loginUsingId($currentOrder->user_id);
        if ($stats['status'] == 2) {

            $currentOrder->update([
                'order_status' => intval(str_replace(',', '', $currentOrder->deposit_price)) != 0 ? "deposit_paid" : "paid"
            ]);

            self::inventory($currentOrder);

            if (Auth::user()->mobile) {
                $kavenegar = new Sms();
                $kavenegar->sendLookup(
                    "userBuy",
                    [
                        "token" => "$currentOrder->id",
                    ],
                    Auth::user()->mobile
                );
            }

            $admin_mobile = Setting::where('key', 'admin_mobile')->whereNotNull('value')->first();
            if ($admin_mobile) {
                $kavenegar = new Sms();
                $kavenegar->sendLookup(
                    "adminBuy",
                    [
                        "token" => "$currentOrder->id",

                    ],
                    $admin_mobile->value
                );
            }


            return redirect(route('basket.success', ['id' => $currentOrder->id]));
        } else {

            $currentOrder->delete();
            return redirect(route('basket.failed'))->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
        }


    }

    public static function finishSadad($currentOrder, $stats)
    {
        Auth::loginUsingId($currentOrder->user_id);
        $bank = Bank::findOrfail($currentOrder['bank_id']);
        $sadad_gateway = new Sadad($bank);
        $result = $sadad_gateway->verifyTransaction(@$stats['token']);
        if ($result['status'] == "failed") {
            $currentOrder->delete();
            return redirect(route('basket.failed'))->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
        } else {
            $currentOrder->update([
                'order_status' => intval(str_replace(',', '', $currentOrder->deposit_price)) != 0 ? "deposit_paid" : "paid"
            ]);

            self::inventory($currentOrder);

            if (Auth::user()->mobile) {
                $kavenegar = new Sms();
                $kavenegar->sendLookup(
                    "userBuy",
                    [
                        "token" => "$currentOrder->id",
                    ],
                    Auth::user()->mobile
                );
            }

            $admin_mobile = Setting::where('key', 'admin_mobile')->whereNotNull('value')->first();
            if ($admin_mobile) {
                $kavenegar = new Sms();
                $kavenegar->sendLookup(
                    "adminBuy",
                    [
                        "token" => "$currentOrder->id",

                    ],
                    $admin_mobile->value
                );
            }


            if (intval(str_replace(',', '', $currentOrder->deposit_price)) != 0) {
                return redirect(route('basket.order-images', ['id' => $currentOrder->id]));
            } else {
                return redirect(route('basket.success', ['id' => $currentOrder->id]));
            }


        }


    }
    public static function snappPayInquiry($order){
        $site = SiteHelper::getInformation();
        $bank = Bank::findOrfail($order['bank_id']);
        $inquiry = new SnappPay($bank,$site['site_name']??'');
        return $inquiry->inquiry($order);
    }
}

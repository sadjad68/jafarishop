<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Modules\Order\Http\Resources\BasketCollection;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Http\Requests\OrderImageRequest;
use App\Modules\Order\Services\BankService;
use App\Modules\Order\Services\BasketService;
use App\Modules\Order\Services\DiscountService;
use App\Modules\Order\Services\OrderService;
use App\Modules\Order\Services\SnappPayService;
use App\Modules\Setting\Services\SettingService;
use App\Services\EcommerceTracking\EcommerceItemMapper;
use App\Services\EcommerceTracking\EcommerceTrackingService;


class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    //cart
    public function cart()
    {
        $basket = BasketService::findBasketAndCheckStock();
        if (!isset($basket)) {
            return redirect()->route('basket.cart');
        }
        if ($basket->address_id == null) {
            return redirect()->route('basket.shipping')->with('error', 'لطفا ابتدا آدرس را انتخاب کنید');
        }
        if (count(@$basket->address->city->shippingMethods) == 0) {
            return redirect()->route('basket.shipping')->with('error', 'روش ارسال اجباریست لطفا با پشتیبانی تماس بگیرید');
        }
        if (count(@$basket->address->city->shippingMethods) > 0 && $basket->shipping_method_id == null) {
            return redirect()->route('basket.shipping')->with('error', 'لطفا ابتدا روش ارسال را انتخاب کنید');
        }


        $items = $basket ? new BasketCollection(@$basket->items) : [];

        $banks = BankService::findAllActive();
        $banks->each(function ($bank) {
            if ($bank->bank_type === 'cardtocard') {
                $config = json_decode($bank->config ?: '{}', true) ?: [];
                $bank->setAttribute('card_number', $config['card_number'] ?? '');
                $bank->setAttribute('shaba_number', $config['shaba_number'] ?? '');
                $bank->setAttribute('account_holder_name', $config['account_holder_name'] ?? '');
            }
            $bank->makeHidden('config');
        });

        return view('pages.cart.payment', compact('basket', 'items', 'banks'));
    }

    public function addDiscount(Request $request)
    {
        return DiscountService::checkDiscount($request->get('discount_code'));
    }

    public function deleteDiscount(Request $request)
    {
        $basket = BasketService::findBasketAndCheckStock();
        $basket->update([
            'discount_id' => null
        ]);
    }

    //price
    public function orderPrice(Request $request)
    {
        try {
            $bankId = $request->get('bank_id') ? (int) $request->get('bank_id') : null;
            $price = DiscountService::listPrice($bankId);
            $banks = BankService::findAllActive();
            $basket = BasketService::findBasketAndCheckStock();
            $price['snapp_data'] = [];
            if (in_array('snappay', $banks->pluck('bank_type')->toArray())) {
                $snapp_price = $price['final_price_sum'];
                $price['snapp_data'] = SnappPayService::checkSnappay($snapp_price);
            }
            return response()->json($price);
        } catch (\Exception $err) {
            $basket = BasketService::findBasketAndCheckStock();
            $basket->update([
                'shipping_method_id' => null
            ]);
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    //create
    public function create(Request $request)
    {
        $checkout_settings = SettingService::getFormatSettings([
            'cart_terms_acceptance_enabled',
        ]);
        if ((int) (@$checkout_settings['cart_terms_acceptance_enabled']) === 1 && ! $request->boolean('terms_accepted')) {
            return redirect()->back()->with('error', 'ابتدا قوانین را مطالعه کنید و تیک قبول را بزنید.');
        }
        if ($request->get('bank_id') == null) {
            return redirect()->back()->with('error', 'درگاه پرداخت مورد نظر خود را انتخاب  کنید');
        }
        $bank = Bank::active()->findOrFail($request->get('bank_id'));
        if ($bank->bank_type === 'cardtocard') {
            $request->validate(
                OrderImageRequest::receiptRules(),
                OrderImageRequest::receiptMessages()
            );
        }
        $basket = BasketService::findBasketAndCheckStock();
        $basket->update([
            'bank_id' => $request->get('bank_id')
        ]);
        try {
            $order = $this->orderService->create($basket, $request->get('user_description'));
            if ($bank->bank_type === 'cardtocard') {
                OrderService::storeImage($order, $request->file('file'));
                OrderService::reserveStock($order);
                $order->update([
                    'deposit_price' => 0,
                    'remaining_price' => 0,
                    'order_status' => 'wait_for_verification',
                ]);
                $basket->delete();

                return redirect()->route('basket.success', ['id' => $order->id]);
            }
            return $this->orderService->checkout($order);
        } catch (\Exception $err) {
            Log::info($err->getMessage());
            return redirect(route('basket.failed'))->with('error', 'خطا در پرداخت، مجدد تلاش نمایید.');
//            return redirect()->back()->with('error',$err->getMessage());
        }
    }

    public function finishZarinPal(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->Authority',$input['Authority'],$input);
    }

    public function finishSaman(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->token',$input['Token'],$input);
    }

    public function finishSadad(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('id',$input['OrderId'],$input);
    }

    public function finishSnappPay(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->transactionId',$input['transactionId'],$input);
    }

    public function finishSaderat(Request $request)
    {
        $input = $request->all();
        $InvoiceID = $input['invoiceid'] ?? $input['InvoiceID'] ?? null;
        return $this->orderService->callback('transaction_info->post->invoiceid',$InvoiceID,$input);
    }
    public function finishIrDargah(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->Authority',$input['authority'],$input);
    }

    public function finishParsian(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->token',$input['Token'],$input);
    }

    public function finishZibal(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->trackId',$input['trackId'],$input);
    }

    public function finishAqayePardakht(Request $request)
    {
        $input = $request->all();
        return $this->orderService->callback('transaction_info->post->transid', $input['transid'], $input);
    }

    public function finishDigiPay(Request $request)
    {
        $input = $request->all();
        $providerId = $input['providerId'] ?? $input['provider_id'] ?? null;
        if ($providerId) {
            return $this->orderService->callback('transaction_info->post->providerId', (string)$providerId, $input);
        }
        $ticket = $input['ticket'] ?? null;
        if ($ticket) {
            return $this->orderService->callback('transaction_info->post->ticket', (string)$ticket, $input);
        }
        return redirect(route('basket.failed'))->with('error', 'کال‌بک درگاه معتبر نیست.');
    }

    //endOrder
    public function success($id)
    {
        $order = OrderService::findById($id);
        if ($order->user_id != Auth::id()) {
            return redirect('/')->with('error', 'این فاکتور متعلق به شما نیست');
        }
        $isPendingCardToCard = @$order->bank->bank_type === 'cardtocard'
            && $order->order_status === 'wait_for_verification';
        if ($order->order_status != 'paid' && ! $isPendingCardToCard) {
            return redirect('/')->with('info', 'برای اطلاعات بیشتر با پشتیبانی تماس بگیرید');
        }
        $ecommerce_purchase = null;
        if (! $isPendingCardToCard && EcommerceTrackingService::isEnabled()) {
            $ecommerce_purchase = EcommerceItemMapper::buildPurchasePayload($order);
        }

        return view('pages.cart.success', compact('order', 'ecommerce_purchase', 'isPendingCardToCard'));
    }

    public function failed()
    {
        return view('pages.cart.failed');
    }

    public function successDeposit($id)
    {
        $order = OrderService::findById($id);
        if ($order->user_id != Auth::id()) {
            return redirect('/')->with('error', 'این فاکتور متعلق به شما نیست');
        }
        $isCardToCard = @$order->bank->bank_type === 'cardtocard' && $order->order_status == "paying";
        if ($order->order_status != "deposit_paid" && !$isCardToCard) {
            return redirect('/')->with('info', 'برای اطلاعات بیشتر با پشتیبانی تماس بگیرید');
        }
        return view('pages.cart.upload-images', compact('order'));
    }

}

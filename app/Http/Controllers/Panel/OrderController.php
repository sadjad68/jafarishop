<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Services\OrderService;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Services\SettingService;

class OrderController extends Controller
{
    public function index()
    {
        $user= Auth::user();
        return view('pages.panel.order.list',compact('user'));
    }
    public function detail($id){
        $order = OrderService::findById($id);
        if ($order->user_id != Auth::id()) {
            abort(404);
        }
        $isCardToCard = @$order->bank->bank_type === 'cardtocard';
        $receipt = $isCardToCard ? @$order->images->first() : null;
        $receiptUrl = $receipt ? asset('uploads/order/' . $order->id . '/' . $receipt->file) : null;
        $receiptIsPdf = $receipt && strtolower(pathinfo($receipt->file, PATHINFO_EXTENSION)) === 'pdf';

        $isAwaitingPaymentVerification = $order->order_status === 'wait_for_verification';
        $paymentStatusColor = $isAwaitingPaymentVerification
            ? '#7c3aed'
            : ([
                'warning' => '#f0ad4e',
                'success' => '#28a745',
                'danger' => '#dc3545',
            ][@$order->status['badge']] ?? '#6c757d');

        $shippingStatusColor = @$order->shipping_status->color ?: '#f0ad4e';

        $receiptStatus = null;
        if ($isCardToCard && $receipt) {
            $badgeColors = [
                'warning' => '#f0ad4e',
                'success' => '#28a745',
                'danger' => '#dc3545',
            ];
            $receiptStatus = match ($order->order_status) {
                'wait_for_verification' => ['title' => 'در انتظار تایید', 'color' => $badgeColors['warning']],
                'paid' => ['title' => 'تایید شده', 'color' => $badgeColors['success']],
                'unpaid' => ['title' => 'رد شده', 'color' => $badgeColors['danger']],
                default => null,
            };
        }

        return view('pages.panel.order.detail', compact(
            'order',
            'isCardToCard',
            'receipt',
            'receiptUrl',
            'receiptIsPdf',
            'isAwaitingPaymentVerification',
            'paymentStatusColor',
            'shippingStatusColor',
            'receiptStatus'
        ));
    }
    public function factor($id){
        $order = OrderService::findById($id);
        if (!$order || $order->user_id != Auth::id()) {
            abort(404, 'سفارش یافت نشد.');
        }
        $settings = SettingService::getFormatSettings(['siteName_fa','logo','main_phone_number','footer_logo']);
        return view('pages.panel.order.factor',compact('order','settings'));
    }

}

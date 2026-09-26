<?php

namespace App\Modules\Order\Http\Controllers;

use Carbon\Carbon;
use CURLFile;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\General\Helper\NumberHelper;
use App\Modules\General\Helper\Sms;
use App\Modules\Order\DTO\ShippingMethodDTO;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\OrderItem;
use App\Modules\Order\Entities\OrderShippingStatus;
use App\Modules\Order\Entities\ShippingMethod;
use App\Modules\Order\Filters\OrderFilter;
use App\Modules\Order\Http\Requests\OrderBijakRequest;
use App\Modules\Order\Http\Requests\ShippingMethodRequest;
use App\Modules\Order\Library\ChaparShipment;
use App\Modules\Order\Library\SnappPay;
use App\Modules\Order\Services\OrderService;
use App\Modules\Order\Services\ZarinpalInquiryService;
use App\Modules\Order\Services\OrderShippingStatusService;
use App\Modules\Order\Services\ReturnService;
use App\Modules\Order\Services\ShippingMethodService;
use App\Modules\Order\Exports\OrderExport;
use App\Modules\Setting\Services\SettingService;
use App\Modules\Setting\Services\SocialService;


class OrderController extends Controller
{
    protected $shippingMethodService;
    protected $orderService;
    public function __construct(
        ShippingMethodService $shippingMethodService,
        ReturnService $returnService,
        OrderService $orderService,
        SocialService  $socialService,
    )
    {
        $this->shippingMethodService = $shippingMethodService;
        $this->orderService = $orderService;
        $this->returnService = $returnService;
        $this->socialService = $socialService;
    }
    /**
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Order::query();
        $shipping_methods = ShippingMethod::orderBy('id','DESC')->get();
        $shipping_statuses = OrderShippingStatus::orderBy('id','DESC')->get();
        if ($request->has(['filter'])) {
            $filters = [
                'full_name' => $request->input('full_name'),
                'shipping_method_id' => $request->input('shipping_method_id'),
                'shipping_status_id' => $request->input('shipping_status_id'),
                'order_status' => $request->input('order_status'),
                'user_id' => $request->input('user_id'),
                'id' => $request->input('id'),
                'transactionId' => $request->input('transactionId'),
                'mobile' => $request->input('mobile'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
            ];
            $query = app(OrderFilter::class)->apply($query, $filters);
        }
        $orders= $query->with(['user', 'bank', 'shipping_method', 'shipping_status'])->orderBy('id','DESC')->paginate(20);
        return view('admin.order.order.index', compact('orders','shipping_statuses','shipping_methods'));

    }
    public function detail(int $id)
    {
        $data = Order::findOrfail($id);
        $shipping_statuses = OrderShippingStatus::orderBy('id','DESC')->get();

        return view('admin.order.order.detail', compact('data','shipping_statuses'));

    }
    public function factor($id)
    {
        $order = Order::findOrfail($id);
        if ($order->order_status == "paying"){
            return redirect()->back()
                ->with('success', ' امکان مشاهده نسخه قابل چاپ وجود ندارد');
        }
        $settings = SettingService::getFormatSettings(['siteName_fa','logo','main_phone_number','footer_logo']);
        return view('admin.order.order.factor', compact('order','settings'));

    }

    public function postLabel($id)
    {
        $data = Order::findOrfail($id);
        $instagramMod = $this->socialService->getInstagram();
        if($instagramMod) {
            $url = $instagramMod->link;
            $path = parse_url($url, PHP_URL_PATH);
            $instagram = trim($path, '/');
            return view('admin.order.post-label.index', compact('data','instagram'));
        }
        return view('admin.order.post-label.index', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function changeShippingStatus(Request $request, $id)
    {
        $data = Order::findOrfail($id);
        if ($data['shipping_status_id'] != $request->get('shipping_status_id')){
            $status = OrderShippingStatusService::findById($request->get('shipping_status_id'));
            $shipping_method = $data->shipping_method;
            $post_code = null;
//            if ($status->for_send == 1){
//                $bulkData = [
//                    'user' => [
//                        'username' => json_decode(@$shipping_method['config'],true)['user_name'],
//                        'password' => json_decode(@$shipping_method['config'],true)['password']
//                    ],
//                    'bulk' => [
//                        [
//                            'cn' => [
//                                'reference' => $data->id,
//                                'date' => Carbon::now()->format('Y-m-d'),
//                                'assinged_pieces' => 0,
//                                'service' => $shipping_method->chapar_type,
//                                'value' => $data->total_price.'0',
//                                'payment_term' => $shipping_method->freight_balance,
//                                'weight' => $data->total_weight,
//                                'content' => '',
//                                'note' => '',
//                                'inv_value' => $data->total_price.'0'
//                            ],
//                            'sender' => [
//                                'person' => $shipping_method->sender_name,
//                                'company' => $shipping_method->sender_company,
//                                'city_no' => $shipping_method->sender_city->chapar_id,
//                                'telephone' => $shipping_method->sender_phone,
//                                'mobile' => $shipping_method->sender_mobile,
//                                'email' => $shipping_method->sender_email,
//                                'address' => $shipping_method->sender_address,
//                                'postcode' => $shipping_method->sender_postal_code
//                            ],
//                            'receiver' => [
//                                'person' => $data->receiptor_full_name,
//                                'company' => '',
//                                'city_no' => $data->city->chapar_id,
//                                'telephone' => '',
//                                'mobile' => json_decode(@$data->address,true) ? json_decode(@$data->address,true)['receiptor_mobile'] :  $data->user->mobile,
//                                'email' => '',
//                                'address' => json_decode(@$data->address,true) ? json_decode(@$data->address,true)['state'].' '.
//                                    json_decode(@$data->address,true)['city'].' '.
//                                    json_decode(@$data->address,true)['address'] :  "",
//                                'postcode' => json_decode(@$data->address,true) ? json_decode(@$data->address,true)['postal_code'] :  ""
//                            ]
//                        ]
//                    ]
//                ];
//
//                $response =  ChaparShipment::bulk(json_encode($bulkData));
//                if (!empty(trim(@$response['objects']['result'][0]['tracking']))) {
//                    $post_code = @$response['objects']['result'][0]['tracking'];
//                }
//                $data->update(
//                    [
//                        'post_code'=>@$post_code
//                    ]
//                );
//            }
            $data->update(
                [
                    'shipping_status_id'=>$request->get('shipping_status_id'),
                ]
            );
            if (@$data->shipping_status->sending_sms == 1 && @$data->user->mobile != null) {
                $status_title =@$data->shipping_status->title;
                $kavenegar = new Sms();

                $kavenegar->sendLookup(
                    "userChangeStatus",
                    [
                        "token"=>"$data->id",
                        "token10"=>"$status_title",
                    ],
                    $data->user->mobile
                );


            }


        }


        return redirect()->route('admin.order.index')
            ->with('success', 'وضعیت ویرایش شد.');

    }
    public function postCode(Request $request){
        $data = Order::findOrFail($request->get('order_id'));
            $data->update([
               'post_code'=>@$request->get('post_code')
            ]);

        if (@$data->user->mobile) {
            $kavenegar = new Sms();

            $kavenegar->sendLookup(
                "sendPostCode",
                [
                    "token" => "$data->id",
                    "token10" => "$data->post_code",
                ],
                $data->user->mobile
            );
        }
        return redirect()->back()
            ->with('success', ' کد پیگیری پستی با موفقیت ارسال شد.');
    }

    public function uploadBijak(OrderBijakRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->order_status !== 'paid') {
            return Redirect::back()->with('error', 'آپلود بیجک فقط برای فاکتورهای پرداخت‌شده امکان‌پذیر است.');
        }

        OrderService::storeBijakImage($order, $request->file('bijak_image'));

        return Redirect::back()->with('success', 'تصویر بیجک ذخیره شد.');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ShippingMethodRequest $request, $id)
    {
        $this->shippingMethodService->update($id, ShippingMethodDTO::fromRequest($request));
        return redirect()->route('admin.order.index')
            ->with('success', 'وضعیت ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->shippingMethodService->deleteOne($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
    public function convertDates() {
        $orders = Order::orderBy('id', 'DESC')->get();

        foreach ($orders as $order) {
            // مقدار اولیه updated_at را ذخیره می‌کنیم
            $originalUpdatedAt = $order->updated_at;

            // افزودن 3 ساعت و 30 دقیقه به created_at و updated_at
            $newCreatedAt = $order->created_at->addHours(3)->addMinutes(30);
            $newUpdatedAt = $order->updated_at->addHours(3)->addMinutes(30);
            // غیرفعال کردن timestamps موقتاً
            $order->timestamps = false;

            // آپدیت مقادیر
            $order->update([
                'created_at' => $newCreatedAt,
                'updated_at' => $newUpdatedAt
            ]);
            // بازگرداندن مقدار اولیه updated_at
            $order->timestamps = false;
            $order->update(['updated_at' => $originalUpdatedAt]);
        }
    }
    public function imageAccept($id)
    {
        $order = Order::findOrfail($id);
        if ($order->order_status !== 'wait_for_verification') {
            return Redirect::back()->with('error', 'وضعیت سفارش برای تایید فیش معتبر نیست.');
        }
        $stockWarnings = [];
        if (@$order->bank->bank_type === 'cardtocard') {
            $stockWarnings = OrderService::fulfillStockOnCardToCardConfirm($order);
        }
        $order->update([
           'order_status'=>"paid"
        ]);

        $receiptAcceptAlert = [
            'message' => 'فیش واریز تایید شد.',
        ];
        if (!empty($stockWarnings)) {
            $receiptAcceptAlert['warning'] = 'هشدار: ' . implode(' ', $stockWarnings);
        }

        return Redirect::back()->with('receipt_accept_alert', $receiptAcceptAlert);

    }
    public function imageDecline($id)
    {
        $order = Order::findOrfail($id);
        if ($order->order_status !== 'wait_for_verification') {
            return Redirect::back()->with('error', 'وضعیت سفارش برای رد فیش معتبر نیست.');
        }
        if (@$order->bank->bank_type === 'cardtocard') {
            OrderService::releaseStockReservation($order, 'released');
        } else {
            foreach ($order->items as $item) {
                $this->orderService->reverseStockAndPrice($item);
            }
        }
        $order->update([
            'order_status'=>"unpaid"
        ]);
        return Redirect::back()->with('success', 'فیش واریز رد شد.');

    }
    public function orderReturn($id){
        $order = Order::findOrfail($id);

        foreach ($order->items as $item) {
            $this->returnService->returnWithCount($item,0);
        }
        if($order->bank->bank_type == "snappay"){
            $cancelation = $this->returnService->snappPayCancelation($order);
            if ($cancelation->getSuccessful() == "false") {
                $message = @$cancelation->getErrorData()['errorCode']. ':'. @$cancelation->getErrorData()['message'];
                return Redirect::back()->with('error', $message);
            }
        }
        if ($order->bank->bank_type === 'digipay') {
            $orderFresh = $order->fresh();
            $apiAmount = $this->returnService->digiPayRemainingApiRefundable($orderFresh);
            $digi = $this->returnService->digiPayRefundForOrder($orderFresh, $apiAmount, 'full-' . time());
            if (!$digi['success']) {
                return Redirect::back()->with('error', $digi['message'] ?? 'خطا در بازگشت وجه دیجی‌پی');
            }
        }
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت مرجوع شد');
    }
    public function return(Request $request,$id){
        $item = OrderItem::findOrfail($id);
        $quantity = NumberHelper::persian2LatinDigit($request->get('quantity'));
        if ($item->quantity < $quantity){
            return Redirect::back()->with('error', 'مقدار وارد شده بیشتر از تعداد محصول میباشد');

        }
        $order = $item->order;
        $returnedQty = (int) $item->quantity - (int) $quantity;

        $this->returnService->returnWithCount($item,$quantity);
        if($order->bank->bank_type == "snappay"){
            $cancelation = $this->returnService->snappPayUpdate($order);
            if ($cancelation->getSuccessful() == "false") {
                $message = @$cancelation->getErrorData()['errorCode']. ':'. @$cancelation->getErrorData()['message'];
                return Redirect::back()->with('error', $message);
            }
        }
        if ($order->bank->bank_type === 'digipay' && $returnedQty > 0) {
            $order = $order->fresh();
            $refundTomans = $this->returnService->orderItemRefundMerchandiseTomans($item->fresh(), $returnedQty);
            $apiAmount = $this->returnService->digiPayRefundApiAmountFromMerchandiseTomans($order, $refundTomans);
            $digi = $this->returnService->digiPayRefundForOrder($order, $apiAmount, 'item-' . $item->id . '-' . time());
            if (!$digi['success']) {
                return Redirect::back()->with('error', $digi['message'] ?? 'خطا در بازگشت وجه دیجی‌پی');
            }
        }

        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت مرجوع شد');
    }
    public function export(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '360000');

        return Excel::download(new OrderExport($request), 'orders.xlsx');
    }

    public function inquireZarinpalPayments(string $token)
    {
        $expected = (string) env('AUTH_TOKEN', '');
        if ($expected === '' || !hash_equals($expected, $token)) {
            abort(403, 'Unauthorized.');
        }

        @set_time_limit(120);

        return response()->json(ZarinpalInquiryService::runSweep());
    }
}

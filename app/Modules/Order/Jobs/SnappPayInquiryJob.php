<?php

namespace App\Modules\Order\Jobs;

use App\Library\SiteHelper;
use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Library\SnappPay;
use App\Modules\Order\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SnappPayInquiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    private const FAILED_STATUSES = ["INIT", "REGISTERED", "REVERT", "CANCEL"];

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $siteName = SiteHelper::siteName();

        $my_order = OrderService::byId($this->order);

        if ($my_order) {
            if (!in_array($my_order->order_status, ["paying"])) {
                \Log::info("Skipping CheckTransactionStatus for order ID {$this->order} as it has not valid status.");
                return;
            }
            $bank = Bank::findOrfail($my_order['bank_id']);
            $inquiryResp = OrderService::snappPayInquiry($my_order);
            $inquirySuccessful = @$inquiryResp['successful'] === true;
            $inquiryStatus = @$inquiryResp['response']['status'];
            if (!$inquirySuccessful || ($inquiryStatus && in_array($inquiryStatus, self::FAILED_STATUSES, true))) {
                Log::info('SnappPay order cancel or not successful at inquiry', ['status' => $inquiryStatus]);
                $my_order->update([
                    'order_status' => 'unpaid',
                ]);
            }
            else{
                $snapp = new SnappPay($bank, $siteName);
                return $snapp->verify($my_order);
            }
        }
    }
}

<?php

namespace App\Modules\Order\Jobs;

use App\Library\SiteHelper;
use App\Modules\Order\Services\ZarinpalInquiryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ZarinpalInquiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = 120;

    protected $order;
    protected $siteName;

    public function __construct($order, $siteName = null)
    {
        $this->order = $order;
        $this->siteName = $siteName;
    }

    public function handle()
    {
        $siteName = $this->siteName ?: SiteHelper::siteName();
        $myOrder = ZarinpalInquiryService::findOrder($this->order, $siteName);
        if (!$myOrder) {
            Log::warning('ZarinPal inquiry job skipped; order not found', [
                'order_id' => $this->order,
                'site' => $siteName,
            ]);

            return;
        }

        $action = ZarinpalInquiryService::processOrder($myOrder, $siteName);
        if ($action === ZarinpalInquiryService::ACTION_RETRY) {
            $this->release($this->backoff);
        }
    }
}

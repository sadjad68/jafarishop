<?php

namespace App\Console\Commands;

use App\Modules\Order\Services\ZarinpalInquiryService;
use Illuminate\Console\Command;

class InquireZarinpalPayments extends Command
{
    protected $signature = 'order:inquire-zarinpal-payments';
    protected $description = 'Inquire ZarinPal for paying orders older than 15 minutes and settle or expire them';

    public function handle()
    {
        $result = ZarinpalInquiryService::runSweep();

        foreach ($result['sites'] as $summary) {
            $this->info(sprintf(
                'Site %s: scanned=%d paid=%d unpaid=%d wait=%d retry=%d skipped=%d',
                $summary['site'] ?? '-',
                $summary['scanned'] ?? 0,
                $summary[ZarinpalInquiryService::ACTION_VERIFY_AND_PAY] ?? 0,
                $summary[ZarinpalInquiryService::ACTION_UNPAID] ?? 0,
                $summary[ZarinpalInquiryService::ACTION_WAIT] ?? 0,
                $summary[ZarinpalInquiryService::ACTION_RETRY] ?? 0,
                $summary[ZarinpalInquiryService::ACTION_SKIP] ?? 0
            ));
        }

        $this->info('ZarinPal inquiry finished for ' . ($result['total'] ?? 0) . ' order(s).');

        return self::SUCCESS;
    }
}

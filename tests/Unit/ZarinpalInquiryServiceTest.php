<?php

namespace Tests\Unit;

use App\Modules\Order\Entities\Bank;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Library\ZarinPal;
use App\Modules\Order\Services\ZarinpalInquiryService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZarinpalInquiryServiceTest extends TestCase
{
    public function test_decide_action_verifies_paid_and_verified_statuses(): void
    {
        $this->assertSame(
            ZarinpalInquiryService::ACTION_VERIFY_AND_PAY,
            ZarinpalInquiryService::decideAction('PAID', true, 15, true)
        );
        $this->assertSame(
            ZarinpalInquiryService::ACTION_VERIFY_AND_PAY,
            ZarinpalInquiryService::decideAction('verified', true, 20, true)
        );
    }

    public function test_decide_action_marks_failed_and_reversed_unpaid(): void
    {
        $this->assertSame(
            ZarinpalInquiryService::ACTION_UNPAID,
            ZarinpalInquiryService::decideAction('FAILED', true, 15, true)
        );
        $this->assertSame(
            ZarinpalInquiryService::ACTION_UNPAID,
            ZarinpalInquiryService::decideAction('REVERSED', true, 15, true)
        );
    }

    public function test_decide_action_waits_for_in_bank_until_expire(): void
    {
        $this->assertSame(
            ZarinpalInquiryService::ACTION_WAIT,
            ZarinpalInquiryService::decideAction('IN_BANK', true, 15, true)
        );
        $this->assertSame(
            ZarinpalInquiryService::ACTION_UNPAID,
            ZarinpalInquiryService::decideAction('IN_BANK', true, 30, true)
        );
    }

    public function test_decide_action_retries_when_inquiry_http_fails(): void
    {
        $this->assertSame(
            ZarinpalInquiryService::ACTION_RETRY,
            ZarinpalInquiryService::decideAction(null, false, 20, true)
        );
    }

    public function test_decide_action_marks_unpaid_without_authority(): void
    {
        $this->assertSame(
            ZarinpalInquiryService::ACTION_UNPAID,
            ZarinpalInquiryService::decideAction(null, true, 15, false)
        );
    }

    public function test_extract_authority_from_json_string_and_array(): void
    {
        $fromJson = new Order();
        $fromJson->setRawAttributes([
            'transaction_info' => json_encode(['post' => ['Authority' => 'A123']]),
        ], true);

        $fromArray = new Order();
        $fromArray->transaction_info = ['post' => ['authority' => 'B456']];

        $this->assertSame('A123', ZarinpalInquiryService::extractAuthority($fromJson));
        $this->assertSame('B456', ZarinpalInquiryService::extractAuthority($fromArray));
        $this->assertNull(ZarinpalInquiryService::extractAuthority(new Order()));
    }

    public function test_zarinpal_inquiry_returns_gateway_status_instead_of_treating_in_bank_as_failed(): void
    {
        Http::fake([
            'https://payment.zarinpal.com/pg/v4/payment/inquiry.json' => Http::response([
                'data' => [
                    'status' => 'IN_BANK',
                    'code' => 100,
                    'message' => 'Success',
                ],
                'errors' => [],
            ], 200),
        ]);

        $bank = new Bank();
        $bank->setRawAttributes([
            'config' => json_encode(['MerchantId' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx']),
        ], true);

        $result = (new ZarinPal($bank, 'template'))->inquiry('A000000000000000000000000000xpgr85j5');

        $this->assertTrue($result['ok']);
        $this->assertSame('IN_BANK', $result['status']);
        $this->assertNotSame('failed', $result['status']);
    }

    public function test_zarinpal_inquiry_http_failure_is_retryable(): void
    {
        Http::fake([
            'https://payment.zarinpal.com/pg/v4/payment/inquiry.json' => Http::response(null, 500),
        ]);

        $bank = new Bank();
        $bank->setRawAttributes([
            'config' => json_encode(['MerchantId' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx']),
        ], true);

        $result = (new ZarinPal($bank, 'template'))->inquiry('A000000000000000000000000000xpgr85j5');

        $this->assertFalse($result['ok']);
        $this->assertNull($result['status']);
    }
}

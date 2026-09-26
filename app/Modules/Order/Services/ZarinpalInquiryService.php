<?php

namespace App\Modules\Order\Services;

use App\Library\SiteHelper;
use App\Modules\General\Helper\Sms;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Library\GatewayAmountHelper;
use App\Modules\Order\Library\ZarinPal;
use App\Modules\Setting\Entities\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ZarinpalInquiryService
{
    public const INQUIRY_DELAY_MINUTES = 15;
    public const IN_BANK_EXPIRE_MINUTES = 30;
    public const BATCH_LIMIT = 50;

    public const ACTION_VERIFY_AND_PAY = 'verify_and_pay';
    public const ACTION_UNPAID = 'unpaid';
    public const ACTION_WAIT = 'wait';
    public const ACTION_RETRY = 'retry';
    public const ACTION_SKIP = 'skip';

    public static function decideAction(?string $inquiryStatus, bool $inquiryOk, int $ageMinutes, bool $hasAuthority): string
    {
        if (!$hasAuthority) {
            return self::ACTION_UNPAID;
        }

        if (!$inquiryOk) {
            return self::ACTION_RETRY;
        }

        $status = strtoupper(trim((string) $inquiryStatus));

        return match ($status) {
            'PAID', 'VERIFIED' => self::ACTION_VERIFY_AND_PAY,
            'FAILED', 'REVERSED' => self::ACTION_UNPAID,
            'IN_BANK' => $ageMinutes >= self::IN_BANK_EXPIRE_MINUTES
                ? self::ACTION_UNPAID
                : self::ACTION_WAIT,
            default => $ageMinutes >= self::INQUIRY_DELAY_MINUTES
                ? self::ACTION_UNPAID
                : self::ACTION_RETRY,
        };
    }

    public static function extractAuthority(Order $order): ?string
    {
        $info = $order->transaction_info;
        if (is_string($info) && $info !== '') {
            $info = json_decode($info, true);
        }

        if (!is_array($info)) {
            return null;
        }

        $authority = $info['post']['Authority'] ?? $info['post']['authority'] ?? null;
        if (!is_string($authority) || trim($authority) === '') {
            return null;
        }

        return $authority;
    }

    public static function findOrder($orderId, ?string $siteName = null): ?Order
    {
        return OrderService::byId($orderId);
    }

    public static function processOrder(Order $order, string $siteName): string
    {
        if (!in_array($order->order_status, ['paying'], true)) {
            Log::info('ZarinPal inquiry skipped; order is not paying', [
                'order_id' => $order->id,
                'order_status' => $order->order_status,
            ]);

            return self::ACTION_SKIP;
        }

        $order->loadMissing(['bank', 'user', 'items']);
        $bank = $order->bank;
        if (!$bank || $bank->bank_type !== 'zarinPal') {
            Log::info('ZarinPal inquiry skipped; bank is not ZarinPal', [
                'order_id' => $order->id,
                'bank_type' => $bank->bank_type ?? null,
            ]);

            return self::ACTION_SKIP;
        }

        $authority = self::extractAuthority($order);
        $ageMinutes = self::orderAgeMinutes($order);

        if ($authority === null) {
            self::markUnpaid($order);
            Log::warning('ZarinPal inquiry marked unpaid; missing authority', [
                'order_id' => $order->id,
            ]);

            return self::ACTION_UNPAID;
        }

        $gateway = new ZarinPal($bank, $siteName);
        $inquiry = $gateway->inquiry($authority);
        $action = self::decideAction(
            $inquiry['status'] ?? null,
            (bool) ($inquiry['ok'] ?? false),
            $ageMinutes,
            true
        );

        Log::info('ZarinPal inquiry result', [
            'order_id' => $order->id,
            'inquiry_status' => $inquiry['status'] ?? null,
            'inquiry_ok' => $inquiry['ok'] ?? false,
            'action' => $action,
            'age_minutes' => $ageMinutes,
        ]);

        if ($action === self::ACTION_VERIFY_AND_PAY) {
            return self::verifyAndMarkPaid($order, $gateway, $authority);
        }

        if ($action === self::ACTION_UNPAID) {
            self::markUnpaid($order);

            return self::ACTION_UNPAID;
        }

        return $action;
    }

    public static function processPendingOrders(?string $siteName = null): array
    {
        $siteName = $siteName ?: SiteHelper::siteName();

        $run = function () use ($siteName) {
            $orders = Order::query()
                ->where('order_status', 'paying')
                ->where('created_at', '<=', Carbon::now()->subMinutes(self::INQUIRY_DELAY_MINUTES))
                ->whereHas('bank', function ($query) {
                    $query->where('bank_type', 'zarinPal');
                })
                ->with(['bank', 'user', 'items'])
                ->orderBy('id')
                ->limit(self::BATCH_LIMIT)
                ->get();

            $counts = [
                'site' => $siteName,
                'database' => config('database.connections.mysql.database'),
                'scanned' => $orders->count(),
                self::ACTION_VERIFY_AND_PAY => 0,
                self::ACTION_UNPAID => 0,
                self::ACTION_WAIT => 0,
                self::ACTION_RETRY => 0,
                self::ACTION_SKIP => 0,
            ];

            foreach ($orders as $order) {
                try {
                    $action = self::processOrder($order, $siteName);
                    $counts[$action] = ($counts[$action] ?? 0) + 1;
                } catch (\Throwable $e) {
                    $counts[self::ACTION_RETRY]++;
                    Log::error('ZarinPal inquiry sweep failed for order', [
                        'order_id' => $order->id,
                        'site' => $siteName,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return $counts;
        };

        return $run();
    }

    public static function processAllSites(): array
    {
        return [self::processPendingOrders()];
    }

    public static function runSweep(): array
    {
        $merged = [];
        $grandTotal = 0;

        do {
            $batchTotal = 0;
            $progress = 0;

            foreach (self::processAllSites() as $summary) {
                $site = (string) ($summary['site'] ?? '-');
                if (!isset($merged[$site])) {
                    $merged[$site] = [
                        'site' => $site,
                        'database' => $summary['database'] ?? null,
                        'scanned' => 0,
                        self::ACTION_VERIFY_AND_PAY => 0,
                        self::ACTION_UNPAID => 0,
                        self::ACTION_WAIT => 0,
                        self::ACTION_RETRY => 0,
                        self::ACTION_SKIP => 0,
                    ];
                }

                $scanned = (int) ($summary['scanned'] ?? 0);
                $merged[$site]['scanned'] += $scanned;
                $merged[$site][self::ACTION_VERIFY_AND_PAY] += (int) ($summary[self::ACTION_VERIFY_AND_PAY] ?? 0);
                $merged[$site][self::ACTION_UNPAID] += (int) ($summary[self::ACTION_UNPAID] ?? 0);
                $merged[$site][self::ACTION_WAIT] += (int) ($summary[self::ACTION_WAIT] ?? 0);
                $merged[$site][self::ACTION_RETRY] += (int) ($summary[self::ACTION_RETRY] ?? 0);
                $merged[$site][self::ACTION_SKIP] += (int) ($summary[self::ACTION_SKIP] ?? 0);

                $batchTotal += $scanned;
                $progress += (int) ($summary[self::ACTION_VERIFY_AND_PAY] ?? 0)
                    + (int) ($summary[self::ACTION_UNPAID] ?? 0);
            }

            $grandTotal += $batchTotal;
        } while ($progress > 0 && $batchTotal >= self::BATCH_LIMIT);

        return [
            'ok' => true,
            'total' => $grandTotal,
            'sites' => array_values($merged),
        ];
    }

    public static function siteNames(): array
    {
        $names = [];
        $current = (string) config('database.connections.mysql.database');
        if (str_starts_with($current, 'cms_')) {
            $names[] = substr($current, 4);
        } elseif ($current !== '') {
            $names[] = $current;
        }

        try {
            $json = Storage::disk('local')->get('private/config-sites-data.json');
            $sites = json_decode($json, true) ?: [];
            foreach ($sites as $site) {
                if (!empty($site['site_name'])) {
                    $names[] = $site['site_name'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ZarinPal inquiry could not read site config', [
                'error' => $e->getMessage(),
            ]);
        }

        if (!$names) {
            $names[] = SiteHelper::siteName();
        }

        return array_values(array_unique($names));
    }

    private static function verifyAndMarkPaid(Order $order, ZarinPal $gateway, string $authority): string
    {
        $price = GatewayAmountHelper::resolvePaymentToman(
            GatewayAmountHelper::normalizeToman($order->payment_price),
            $order
        );
        $verify = $gateway->verifyTransaction([
            'Status' => 'OK',
            'Authority' => $authority,
        ], $price, $order);

        if ($verify->getStatus() !== 'success') {
            Log::warning('ZarinPal inquiry found PAID/VERIFIED but verify failed', [
                'order_id' => $order->id,
                'verify' => $verify->getVerifyData(),
            ]);

            return self::ACTION_RETRY;
        }

        $refId = $verify->getVerifyData()['RefID'] ?? null;
        self::markPaid($order, $authority, $refId);

        return self::ACTION_VERIFY_AND_PAY;
    }

    private static function markPaid(Order $order, string $authority, $refId): void
    {
        $info = $order->transaction_info;
        if (is_string($info) && $info !== '') {
            $info = json_decode($info, true);
        }
        if (!is_array($info)) {
            $info = [];
        }

        $info['post'] = array_merge($info['post'] ?? [], ['Authority' => $authority]);
        $info['verify'] = array_merge($info['verify'] ?? [], ['RefID' => $refId]);

        $order->update([
            'transaction_info' => $info,
            'order_status' => GatewayAmountHelper::normalizeToman($order->deposit_price) !== 0
                ? 'deposit_paid'
                : 'paid',
        ]);

        OrderService::inventory($order);
        self::notifyPurchase($order);
    }

    private static function markUnpaid(Order $order): void
    {
        $order->update([
            'order_status' => 'unpaid',
        ]);
    }

    private static function notifyPurchase(Order $order): void
    {
        try {
            $order->loadMissing('user');
            if (@$order->user->mobile) {
                $sms = new Sms();
                $sms->sendLookup('userBuy', ['token' => (string) $order->id], $order->user->mobile);
            }

            $adminMobile = Setting::where('key', 'admin_mobile')->whereNotNull('value')->first();
            if ($adminMobile) {
                $sms = new Sms();
                $sms->sendLookup('adminBuy', ['token' => (string) $order->id], $adminMobile->value);
            }
        } catch (\Throwable $e) {
            Log::error('ZarinPal purchase SMS failed after successful verify', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private static function orderAgeMinutes(Order $order): int
    {
        if (!$order->created_at) {
            return self::INQUIRY_DELAY_MINUTES;
        }

        return (int) $order->created_at->diffInMinutes(Carbon::now());
    }

}

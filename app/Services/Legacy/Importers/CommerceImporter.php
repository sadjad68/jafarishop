<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class CommerceImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $this->discounts($stats);
        $deliveredStatusId = $this->deliveredStatusId();
        $context = $this->context($deliveredStatusId);
        $this->orders($stats, $context);
        $this->orderItems($stats);
        $this->serviceOrders($stats);
        foreach (['discounts', 'orders', 'order_items', 'order_shipping_statuses', 'order_histories', 'service_requests'] as $table) {
            $this->support->realignAutoIncrement($table);
        }

        return $stats;
    }

    private function discounts(LegacyImportStats $stats): void
    {
        $this->support->eachPending('discounts', function ($row) use ($stats) {
            $id = (int) $row->id;
            $code = trim((string) ($row->code ?? ''));
            if ($code === '') {
                $this->support->mark('discounts', $id);
                $stats->skipped++;

                return;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $used = (int) ($row->used ?? 0) === 1;
            $this->support->copyRow('discounts', $id, 'discounts', [
                'id' => $id,
                'title' => $code,
                'amount' => LegacyMapper::decimalString($row->amount ?? 0) ?? '0',
                'type' => LegacyMapper::discountType($row->type ?? null),
                'count' => $used ? 0 : 1,
                'basket_minimum_price' => null,
                'first_purchase' => 0,
                'with_discount' => 0,
                'user_id' => null,
                'max_usage_per_user' => 1,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }

    private function deliveredStatusId(): int
    {
        $existing = $this->support->new()->table('order_shipping_statuses')
            ->where('title', 'تحویل داده شده')
            ->value('id');
        if ($existing) {
            return (int) $existing;
        }

        return (int) $this->support->new()->table('order_shipping_statuses')->insertGetId([
            'title' => 'تحویل داده شده',
            'color' => 'success',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ]);
    }

    private function context(int $deliveredStatusId): array
    {
        $transactions = [];
        foreach ($this->support->old()->table('transactions')->get() as $transaction) {
            $transactions[(int) $transaction->id] = $transaction;
        }

        $discountIds = [];
        foreach ($this->support->new()->table('discounts')->pluck('id') as $id) {
            $discountIds[(int) $id] = true;
        }

        $users = [];
        foreach ($this->support->new()->table('users')->get(['id', 'full_name', 'mobile']) as $user) {
            $users[(int) $user->id] = $user;
        }

        $addresses = [];
        foreach ($this->support->new()->table('addresses')->orderBy('id')->get() as $address) {
            $userId = (int) $address->user_id;
            if (!isset($addresses[$userId])) {
                $addresses[$userId] = $address;
            }
        }

        $states = [];
        foreach ($this->support->new()->table('states')->pluck('name', 'id') as $id => $name) {
            $states[(int) $id] = $name;
        }
        $cities = [];
        foreach ($this->support->new()->table('cities')->pluck('name', 'id') as $id => $name) {
            $cities[(int) $id] = $name;
        }

        return compact('transactions', 'discountIds', 'users', 'addresses', 'states', 'cities', 'deliveredStatusId');
    }

    private function orders(LegacyImportStats $stats, array $context): void
    {
        $this->support->eachPending('orders', function ($row) use ($stats, $context) {
            $id = (int) $row->id;
            $userId = LegacyMapper::positiveInt($row->user_id ?? null);
            $user = $userId ? ($context['users'][$userId] ?? null) : null;
            $addressRow = $userId ? ($context['addresses'][$userId] ?? null) : null;
            $orderAddress = trim((string) ($row->address ?? ''));
            $useSavedAddress = $addressRow && trim((string) $addressRow->address) === $orderAddress && $orderAddress !== '';
            $stateId = $useSavedAddress ? LegacyMapper::positiveInt($addressRow->state_id) : null;
            $cityId = $useSavedAddress ? LegacyMapper::positiveInt($addressRow->city_id) : null;
            $receiptorName = null;
            $receiptorMobile = '';
            if ($user) {
                $receiptorName = $user->full_name ?? null;
                $receiptorMobile = (string) ($user->mobile ?? '');
            } elseif ($addressRow) {
                $receiptorName = $addressRow->receiptor_full_name ?? null;
                $receiptorMobile = (string) ($addressRow->receiptor_mobile ?? '');
            }
            $items = (float) ($row->items_total_price ?? 0);
            $tax = (float) ($row->tax1 ?? 0) + (float) ($row->tax2 ?? 0);
            $post = (float) ($row->post_price ?? 0);
            $discountId = LegacyMapper::positiveInt($row->discount_id ?? null);
            if ($discountId && !isset($context['discountIds'][$discountId])) {
                $discountId = null;
            }
            $transactionId = LegacyMapper::positiveInt($row->transaction_id ?? null);
            $transaction = $transactionId ? ($context['transactions'][$transactionId] ?? null) : null;
            $info = [
                'ref_id' => $row->ref_id ?? null,
                'tracking_code' => $row->tracking_code ?? null,
            ];
            if ($transaction) {
                $info['port'] = $transaction->port;
                $info['price'] = $transaction->price;
                $info['gateway_ref_id'] = $transaction->ref_id;
                $info['gateway_tracking_code'] = $transaction->tracking_code;
                $info['card_number'] = $transaction->card_number;
                $info['gateway_status'] = $transaction->status;
                $info['payment_date'] = $transaction->payment_date;
                $info['description'] = $transaction->description;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $delivered = LegacyMapper::isDeliveredOrder($row->order_status_id ?? null);
            $inserted = $this->support->copyRow('orders', $id, 'orders', [
                'id' => $id,
                'user_id' => $userId,
                'address_id' => $useSavedAddress ? (int) $addressRow->id : null,
                'state_id' => $stateId,
                'city_id' => $cityId,
                'address' => json_encode([
                    'state' => $stateId ? ($context['states'][$stateId] ?? '') : '',
                    'city' => $cityId ? ($context['cities'][$cityId] ?? '') : '',
                    'address' => $orderAddress,
                    'receiptor_mobile' => $receiptorMobile,
                    'postal_code' => $useSavedAddress ? ($addressRow->postal_code ?? '') : '',
                ], JSON_UNESCAPED_UNICODE),
                'receiptor_full_name' => $receiptorName,
                'shipping_status_id' => $delivered ? $context['deliveredStatusId'] : null,
                'order_status' => LegacyMapper::orderStatus($row->order_status_id ?? null),
                'discount_id' => $discountId,
                'shipping_price' => LegacyMapper::decimalString($post) ?? '0',
                'total_price' => LegacyMapper::decimalString($items) ?? '0',
                'discount_price' => null,
                'payment_price' => LegacyMapper::decimalString($items + $tax + $post) ?? '0',
                'tax_price' => LegacyMapper::decimalString($tax) ?? '0',
                'user_description' => $row->description ?? null,
                'transaction_info' => json_encode($info, JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);

            if (!$inserted && !$this->support->targetExists('orders', $id)) {
                return;
            }
            $historyExists = $this->support->new()->table('order_histories')
                ->where('order_id', $id)
                ->where('order_status', LegacyMapper::orderStatus($row->order_status_id ?? null))
                ->exists();
            if ($historyExists) {
                return;
            }
            $this->support->new()->table('order_histories')->insert([
                'order_id' => $id,
                'shipping_status_id' => $delivered ? $context['deliveredStatusId'] : null,
                'order_status' => LegacyMapper::orderStatus($row->order_status_id ?? null),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }, 400);
    }

    private function orderItems(LegacyImportStats $stats): void
    {
        $this->support->eachPending('order_items', function ($row) use ($stats) {
            $id = (int) $row->id;
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $price = LegacyMapper::decimalString($row->price ?? 0) ?? '0';
            $this->support->copyRow('order_items', $id, 'order_items', [
                'id' => $id,
                'order_id' => LegacyMapper::positiveInt($row->order_id ?? null),
                'product_id' => LegacyMapper::positiveInt($row->product_id ?? null),
                'product_variant_id' => null,
                'quantity' => (string) ((int) ($row->quantity ?? 0)),
                'price' => $price,
                'discounted_price' => $price,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        }, 500);
    }

    private function serviceOrders(LegacyImportStats $stats): void
    {
        $serviceTitles = [];
        foreach ($this->support->new()->table('services')->get(['id', 'title']) as $service) {
            $serviceTitles[(int) $service->id] = $service->title;
        }
        foreach ($this->support->old()->table('services')->get(['id', 'name']) as $service) {
            $id = (int) $service->id;
            if (!isset($serviceTitles[$id])) {
                $serviceTitles[$id] = $service->name;
            }
        }

        $users = [];
        foreach ($this->support->new()->table('users')->get(['id', 'full_name', 'mobile']) as $user) {
            $users[(int) $user->id] = $user;
        }

        $this->support->eachPending('order', function ($row) use ($stats, $serviceTitles, $users) {
            $id = (int) $row->id;
            $serviceId = LegacyMapper::positiveInt($row->service_id ?? null);
            if ($serviceId && !$this->support->targetExists('services', $serviceId)) {
                $serviceId = null;
            }
            $userId = LegacyMapper::positiveInt($row->user_id ?? null);
            $user = $userId ? ($users[$userId] ?? null) : null;
            $fullName = 'کاربر قدیمی';
            $phone = '00000000000';
            if ($user) {
                $fullName = LegacyMapper::combineText($user->full_name ?? null) ?? 'کاربر قدیمی';
                $mobile = trim((string) ($user->mobile ?? ''));
                if ($mobile !== '') {
                    $phone = $mobile;
                }
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('order', $id, 'service_requests', [
                'id' => $id,
                'full_name' => $fullName,
                'phone' => $phone,
                'description' => LegacyMapper::serviceRequestDescription(
                    $serviceId ? ($serviceTitles[$serviceId] ?? null) : ($serviceTitles[(int) ($row->service_id ?? 0)] ?? null),
                    $row->status ?? null,
                    $row->trans_id ?? null,
                    $row->ref_id ?? null,
                    $row->done_work ?? null
                ),
                'images' => null,
                'is_read' => LegacyMapper::serviceRequestIsRead($row->status ?? null) ? 1 : 0,
                'service_id' => $serviceId,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }
}

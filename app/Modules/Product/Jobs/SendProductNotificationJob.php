<?php

namespace App\Modules\Product\Jobs;

use App\Library\SiteHelper;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductNotification;
use App\Modules\Product\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendProductNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $productIds;

    public function __construct(array $productIds)
    {
        $this->productIds = array_unique($productIds);
    }

    public function handle(): void
    {
        $siteName = SiteHelper::siteName();
        $templateUrl = SiteHelper::templateUrl();

        $service = new NotificationService();

        $products = Product::whereIn('id', $this->productIds)->get();

        foreach ($products as $product) {

            $notifications = ProductNotification::query()
                ->with(['variant'])
                ->where('product_id', $product->id)
                ->where('is_sent', false)
                ->get();

            if ($notifications->isEmpty()) {
                continue;
            }

            $groups = $notifications->groupBy(function ($n) {
                return implode('_', [
                    $n->product_id,
                    $n->product_variant_id ?? 0,
                    $n->type
                ]);
            });

            foreach ($groups as $group) {

                $first = $group->first();
                $ids   = $group->pluck('id')->toArray();

                Log::info("Notification batch started", [
                    'site' => $siteName,
                    'product_id' => $first->product_id,
                    'variant_id' => $first->product_variant_id,
                    'type' => $first->type,
                    'count' => count($ids),
                ]);

                try {

                    if ($first->isAvailability()) {
                        $stock = $first->variant?->stock ?? $product->stock ?? 0;
                        if (intval($stock) <= 0) {
                            Log::info("Notification batch skipped - no stock", [
                                'site' => $siteName,
                                'product_id' => $first->product_id,
                                'variant_id' => $first->product_variant_id,
                                'product_stock' => $product->stock,
                                'variant_stock' => $first->variant?->stock,
                            ]);
                            continue;
                        }
                        $sent = $service->sendBulkAvailabilityArray($ids, $templateUrl);
                    }
                    elseif ($first->isDiscount()) {
                        if (
                            !$product->discounted_price ||
                            $product->discounted_price >= $product->price
                        ) {
                            continue;
                        }
                        $sent = $service->sendBulkSaleArray($ids, $templateUrl);
                    } else {
                        Log::warning("Unknown notification type", [
                            'type' => $first->type,
                            'ids' => $ids
                        ]);
                        continue;
                    }
                    Log::info("Notification batch completed", [
                        'site' => $siteName,
                        'product_id' => $first->product_id,
                        'variant_id' => $first->product_variant_id,
                        'type' => $first->type,
                        'sent' => $sent,
                        'total' => count($ids),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Notification batch failed", [
                        'site' => $siteName,
                        'product_id' => $first->product_id,
                        'variant_id' => $first->product_variant_id,
                        'type' => $first->type,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}

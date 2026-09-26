<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class CatalogImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $this->categories($stats);
        $this->brands($stats);
        $prices = $this->priceMap();
        $this->products($stats, $prices);
        $this->seoRows($stats);
        $this->categorySeo($stats);
        $this->productSeoFallback($stats);
        $this->applyNoindex();
        $this->support->realignAutoIncrement('product_categories');
        $this->support->realignAutoIncrement('brands');
        $this->support->realignAutoIncrement('products');
        $this->support->realignAutoIncrement('seo_metas');

        return $stats;
    }

    private function categories(LegacyImportStats $stats): void
    {
        $this->support->eachPending('categories', function ($row) use ($stats) {
            $id = (int) $row->id;
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('categories', $id, 'product_categories', [
                'id' => $id,
                'title' => LegacyMapper::combineText($row->name ?? null) ?? ('category-' . $id),
                'description' => LegacyMapper::combineText($row->description ?? null, $row->lead ?? null),
                'url' => LegacyMapper::normalizeRedirect($row->url ?? null),
                'image' => null,
                'active' => 1,
                'parent_id' => LegacyMapper::positiveInt($row->parent_id ?? null),
                'sort' => $row->order ?? null,
                'show_in_first_page' => 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }

    private function brands(LegacyImportStats $stats): void
    {
        $this->support->eachPending('brands', function ($row) use ($stats) {
            $id = (int) $row->id;
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $title = LegacyMapper::combineText($row->name ?? null) ?? ('brand-' . $id);
            $this->support->copyRow('brands', $id, 'brands', [
                'id' => $id,
                'title' => $title,
                'description' => $row->description ?? null,
                'url' => LegacyMapper::entitySlug($row->url ?? null, $title, $id, 'brand'),
                'image' => null,
                'active' => 1,
                'show_in_first_page' => (int) ($row->first_page ?? 0) === 1 ? 1 : 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }

    private function priceMap(): array
    {
        $map = [];
        $rows = $this->support->old()->table('product_prices')->orderBy('id')->get(['id', 'product_id', 'price', 'deleted_at']);
        foreach ($rows as $row) {
            $map[(int) $row->product_id][] = [
                'id' => (int) $row->id,
                'price' => $row->price,
                'deleted_at' => $row->deleted_at,
            ];
        }

        return $map;
    }

    private function products(LegacyImportStats $stats, array $prices): void
    {
        $this->support->eachPending('products', function ($row) use ($stats, $prices) {
            $id = (int) $row->id;
            $price = LegacyMapper::latestPrice($prices[$id] ?? [], $row->price ?? null);
            $deletedAt = LegacyMapper::nullableTimestamp($row->deleted_at ?? null);
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $inserted = $this->support->copyRow('products', $id, 'products', [
                'id' => $id,
                'title' => LegacyMapper::combineText($row->name ?? null)
                    ?? LegacyMapper::combineText($row->name2 ?? null)
                    ?? ('product-' . $id),
                'description' => LegacyMapper::combineText($row->description ?? null, $row->description2 ?? null),
                'url' => LegacyMapper::normalizeRedirect($row->url ?? null),
                'image' => null,
                'active' => LegacyMapper::activeForProduct($deletedAt),
                'brand_id' => LegacyMapper::positiveInt($row->brand_id ?? null),
                'price' => $price,
                'discounted_price' => $price,
                'final_price' => $price,
                'stock' => LegacyMapper::stockForPrice($price),
                'show_in_first_page' => (int) ($row->top ?? 0) === 1 ? 1 : 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => $deletedAt,
            ], $stats);

            if (!$inserted && !$this->support->targetExists('products', $id)) {
                return;
            }

            $categoryId = LegacyMapper::positiveInt($row->category_id ?? null);
            if ($categoryId && $this->support->targetExists('product_categories', $categoryId)) {
                $linked = $this->support->new()->table('product_category_product')
                    ->where('product_id', $id)
                    ->where('product_category_id', $categoryId)
                    ->exists();
                if (!$linked) {
                    $this->support->new()->table('product_category_product')->insert([
                        'product_id' => $id,
                        'product_category_id' => $categoryId,
                    ]);
                }
            }

            $parentId = LegacyMapper::positiveInt($row->parent ?? null);
            if ($parentId && $parentId !== $id) {
                $linked = $this->support->new()->table('product_associations')
                    ->where('product_id', $parentId)
                    ->where('related_product_id', $id)
                    ->where('type', 'related')
                    ->exists();
                if (!$linked) {
                    $this->support->new()->table('product_associations')->insert([
                        'product_id' => $parentId,
                        'related_product_id' => $id,
                        'type' => 'related',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }, 200);
    }

    private function seoRows(LegacyImportStats $stats): void
    {
        $this->support->eachPending('seo', function ($row) use ($stats) {
            $id = (int) $row->id;
            $morph = LegacyMapper::seoMorph($row->seoble_type ?? null);
            $seoableId = LegacyMapper::positiveInt($row->seoble_id ?? null);
            if ($morph === null || $seoableId === null) {
                $this->support->mark('seo', $id);
                $stats->skipped++;

                return;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('seo', $id, 'seo_metas', [
                'id' => $id,
                'seoable_type' => $morph,
                'seoable_id' => $seoableId,
                'title_seo' => $row->title ?? null,
                'description_seo' => $row->description ?? null,
                'noindex' => 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);
        });
    }

    private function categorySeo(LegacyImportStats $stats): void
    {
        $rows = $this->support->old()->table('categories')->get(['id', 'seo_title', 'seo_description', 'created_at', 'updated_at', 'deleted_at']);
        foreach ($rows as $row) {
            $title = LegacyMapper::combineText($row->seo_title ?? null);
            $description = LegacyMapper::combineText($row->seo_description ?? null);
            if ($title === null && $description === null) {
                continue;
            }
            $id = (int) $row->id;
            if (!$this->support->targetExists('product_categories', $id)) {
                continue;
            }
            $exists = $this->support->new()->table('seo_metas')
                ->where('seoable_type', LegacyMapper::PRODUCT_CATEGORY)
                ->where('seoable_id', $id)
                ->exists();
            if ($exists) {
                continue;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->insertNew('seo_metas', [
                'seoable_type' => LegacyMapper::PRODUCT_CATEGORY,
                'seoable_id' => $id,
                'title_seo' => $title,
                'description_seo' => $description,
                'noindex' => 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        }
    }

    private function productSeoFallback(LegacyImportStats $stats): void
    {
        $rows = $this->support->old()->table('products')->get(['id', 'seo_title', 'seo_description', 'created_at', 'updated_at']);
        foreach ($rows as $row) {
            $title = LegacyMapper::combineText($row->seo_title ?? null);
            $description = LegacyMapper::combineText($row->seo_description ?? null);
            if ($title === null && $description === null) {
                continue;
            }
            $id = (int) $row->id;
            $exists = $this->support->new()->table('seo_metas')
                ->where('seoable_type', LegacyMapper::PRODUCT)
                ->where('seoable_id', $id)
                ->exists();
            if ($exists || !$this->support->targetExists('products', $id)) {
                continue;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->insertNew('seo_metas', [
                'seoable_type' => LegacyMapper::PRODUCT,
                'seoable_id' => $id,
                'title_seo' => $title,
                'description_seo' => $description,
                'noindex' => 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);
        }
    }

    private function applyNoindex(): void
    {
        $ids = $this->support->old()->table('products')->where('noindex', 1)->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }
        $this->support->new()->table('seo_metas')
            ->where('seoable_type', LegacyMapper::PRODUCT)
            ->whereIn('seoable_id', $ids->all())
            ->update(['noindex' => 1]);
    }
}

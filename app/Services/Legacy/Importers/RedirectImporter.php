<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class RedirectImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $this->legacyRows($stats);
        $this->generated($stats);
        $this->support->realignAutoIncrement('redirects');

        return $stats;
    }

    private function legacyRows(LegacyImportStats $stats): void
    {
        $this->support->eachPending('redirect', function ($row) use ($stats) {
            $id = (int) $row->id;
            $old = LegacyMapper::normalizeRedirect($row->old_address ?? null);
            $new = LegacyMapper::normalizeRedirect($row->new_address ?? null);
            if ($old === null || $new === null || $old === $new) {
                $this->support->mark('redirect', $id);
                $stats->skipped++;

                return;
            }
            if ($this->addressExists($old)) {
                $this->support->mark('redirect', $id);
                $stats->skipped++;

                return;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('redirect', $id, 'redirects', [
                'id' => $id,
                'old_address' => $old,
                'new_address' => $new,
                'type' => '301',
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }

    private function generated(LegacyImportStats $stats): void
    {
        $categories = $this->support->new()->table('product_categories')->get(['id', 'url', 'old_id', 'parent_id']);
        foreach ($categories as $category) {
            $this->storeMany(LegacyMapper::categoryRedirects(
                (int) $category->id,
                $category->url,
                LegacyMapper::positiveInt($category->old_id ?? null),
                $category->parent_id === null
            ), $stats);
        }

        $categoryUrls = [];
        foreach ($categories as $category) {
            $categoryUrls[(int) $category->id] = $category->url;
        }
        $pivots = [];
        foreach ($this->support->new()->table('product_category_product')->get(['product_id', 'product_category_id']) as $pivot) {
            $productId = (int) $pivot->product_id;
            if (!isset($pivots[$productId])) {
                $pivots[$productId] = (int) $pivot->product_category_id;
            }
        }
        foreach ($this->support->new()->table('products')->get(['id', 'url', 'old_id']) as $product) {
            $categoryId = $pivots[(int) $product->id] ?? null;
            $categoryUrl = $categoryId ? ($categoryUrls[$categoryId] ?? null) : null;
            $this->storeMany(LegacyMapper::productRedirects(
                (int) $product->id,
                $product->url,
                $categoryUrl,
                LegacyMapper::positiveInt($product->old_id ?? null)
            ), $stats);
        }

        $blogCategoryUrls = [];
        foreach ($this->support->new()->table('blog_categories')->get(['id', 'url']) as $category) {
            $blogCategoryUrls[(int) $category->id] = $category->url;
        }
        foreach ($this->support->new()->table('blogs')->get(['id', 'url', 'parent_id']) as $blog) {
            $categoryUrl = $blogCategoryUrls[(int) $blog->parent_id] ?? null;
            $this->storeMany(LegacyMapper::postRedirects((int) $blog->id, $blog->url, $categoryUrl), $stats);
        }

        foreach ($this->support->new()->table('brands')->get(['id', 'url']) as $brand) {
            $this->storeMany(LegacyMapper::brandRedirects((int) $brand->id, $brand->url), $stats);
        }
    }

    private function storeMany(array $rows, LegacyImportStats $stats): void
    {
        $now = date('Y-m-d H:i:s');
        foreach ($rows as $row) {
            if ($row['old'] === $row['new'] || $this->addressExists($row['old'])) {
                $stats->skipped++;
                continue;
            }
            $this->support->insertNew('redirects', [
                'old_address' => $row['old'],
                'new_address' => $row['new'],
                'type' => '301',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ], $stats);
        }
    }

    private function addressExists(string $old): bool
    {
        return $this->support->new()->table('redirects')->where('old_address', $old)->exists();
    }
}

<?php

namespace App\Services\Torob;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Modules\Product\Entities\Image;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\SpecificationService;

class TorobProductFormatter
{
    public function formatProduct(Product $product, ?ProductVariant $variant = null): array
    {
        $product->loadMissing([
            'categories',
            'images',
            'specification_values.parent',
            'variants.specifications.parent',
        ]);

        if ($variant) {
            $variant->loadMissing(['specifications.parent', 'images']);
        }

        $availability = $this->isAvailable($product, $variant);
        $currentPrice = $availability ? $this->currentPrice($product, $variant) : 0;

        $title = $product->title;
        if ($variant) {
            $variantTitle = $variant->variant_title ?? '';
            if ($variantTitle && $variantTitle !== 'بدون عنوان') {
                $title = trim($product->title . ' - ' . $variantTitle);
            }
        }

        $payload = [
            'page_unique' => Str::limit($this->pageUnique($product->id, $variant?->id), 200, ''),
            'page_url' => Str::limit($this->absoluteUrl(\App\Library\SiteUrl::product($product, true, $variant ? ['variant' => $variant->id] : [])), 1500, ''),
            'product_group_id' => Str::limit((string)$product->id, 200, ''),
            'title' => Str::limit($title, 500, ''),
            'current_price' => $currentPrice,
            'availability' => $availability,
            'image_links' => $this->imageLinks($product, $variant),
            'date_added' => $this->toIso8601($product->created_at),
            'date_updated' => $this->resolveUpdatedAt($product, $variant),
        ];

        $oldPrice = $this->oldPrice($product, $variant);
        if ($oldPrice !== null) {
            $payload['old_price'] = $oldPrice;
        }

        $categoryName = $product->categories->first()?->title;
        if ($categoryName) {
            $payload['category_name'] = Str::limit($categoryName, 200, '');
        }

        $shortDesc = $this->shortDescription($product);
        if ($shortDesc) {
            $payload['short_desc'] = Str::limit($shortDesc, 500, '');
        }

        $spec = $this->specifications($product, $variant);
        if ($spec !== []) {
            $payload['spec'] = $spec;
        }

        return $payload;
    }

    public function pageUnique(int $productId, ?int $variantId = null): string
    {
        return $variantId ? $productId . '_' . $variantId : (string)$productId;

    }

    public function parsePageUnique(string $pageUnique): ?array
    {
        if (preg_match('/^(\d+)_(\d+)$/', $pageUnique, $matches)) {
            return ['product_id' => (int)$matches[1], 'variant_id' => (int)$matches[2]];

        }

        if (ctype_digit($pageUnique)) {
            return ['product_id' => (int)$pageUnique, 'variant_id' => null];
        }

        return null;
    }

    public function matchesPageUrl(string $requestedUrl, string $productUrl): bool
    {
        if ($this->normalizeUrl($requestedUrl) === $this->normalizeUrl($productUrl)) {
            return true;
        }

        $requestPath = parse_url($this->normalizeUrl($requestedUrl), PHP_URL_PATH) ?: '';
        $productPath = parse_url($this->normalizeUrl($productUrl), PHP_URL_PATH) ?: '';

        return $requestPath !== '' && $requestPath === $productPath;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = $this->absoluteUrl($url);
        }

        $parts = parse_url($url);
        if (is_array($parts) && isset($parts['host'])) {
            $host = preg_replace('/^www\./i', '', strtolower($parts['host']));
            $scheme = strtolower($parts['scheme'] ?? 'https');
            $port = isset($parts['port']) ? ':' . $parts['port'] : '';
            $path = rtrim(strtolower($parts['path'] ?? ''), '/');

            return $scheme . '://' . $host . $port . $path;
        }

        return rtrim(strtolower($url), '/');
    }

    private function absoluteUrl(string $url): string
    {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = url($url);
        }

        if (str_starts_with((string)config('app.url'), 'https://')) {
            $url = preg_replace('/^http:/i', 'https:', $url) ?? $url;
        }

        return $url;
    }

    private function isAvailable(Product $product, ?ProductVariant $variant): bool
    {
        return ($variant ? (int)$variant->stock : (int)$product->stock) > 0;
    }

    private function currentPrice(Product $product, ?ProductVariant $variant): int
    {
        if ($variant) {
            return (int)($variant->final_price ?: $variant->price ?: 0);
        }

        return (int)($product->final_price ?: $product->price ?: 0);
    }

    private function oldPrice(Product $product, ?ProductVariant $variant): ?int
    {
        if ($variant) {
            return (int)$variant->discounted_price !== 0 ? (int)$variant->price : null;
        }

        return (int)$product->discounted_price !== 0 ? (int)$product->price : null;
    }

    /**
     * Main product image first; gallery without thumbnails; absolute URLs (big size).
     */
    private function imageLinks(Product $product, ?ProductVariant $variant): array
    {
        $links = [];

        if (!$variant) {
            $links[] = $this->absoluteUrl($product->getImage('big'));
        }

        $images = $variant && $variant->images->isNotEmpty()
            ? $this->sortedGalleryImages($variant->images)
            : $this->sortedGalleryImages($product->images);

        foreach (ProductService::getProductImagesSizeSeperated($images) as $image) {
            $links[] = $this->absoluteUrl($image['image_big'] ?: $image['image_medium']);
        }

        $links = array_values(array_unique(array_filter($links)));

        if ($links === []) {
            $links[] = $this->absoluteUrl($product->getImage('big'));
        }

        return array_map(fn(string $link) => Str::limit($link, 1000, ''), $links);
    }

    private function sortedGalleryImages(Collection $images): Collection
    {
        return $images
            ->filter(fn(Image $image) => (int)($image->active ?? 1) === 1)
            ->sortBy([
                ['thumbnail', 'asc'],
                ['id', 'asc'],
            ])
            ->values();
    }

    private function shortDescription(Product $product): ?string
    {
        if (!empty($product->description)) {
            return strip_tags($product->description);
        }

        if (!empty($product->seoDescription)) {
            return strip_tags($product->seoDescription);
        }

        return null;
    }

    private function specifications(Product $product, ?ProductVariant $variant): array
    {
        $spec = [];

        if ($variant) {
            foreach ($variant->specifications as $specification) {
                $key = $specification->parent->title ?? 'مشخصات';
                $spec[$key] = $specification->title;
            }
        }

        foreach ($product->specification_values as $row) {
            $key = $row->parent->title ?? '';
            if ($key !== '') {
                $spec[$key] = $row->title;
            }
        }

        foreach (SpecificationService::getFormatTextSpecifications($product) as $specificationGroup) {
            foreach ($specificationGroup as $row) {
                if (!empty($row['specification'])) {
                    $spec[$row['specification']] = (string)$row['value'];
                }
            }
        }

        return array_map(
            fn($value) => is_int($value) ? $value : (string)$value,
            $spec
        );
    }

    private function resolveUpdatedAt(Product $product, ?ProductVariant $variant): string
    {
        $updatedAt = $product->updated_at;

        if ($variant && $variant->updated_at && $variant->updated_at->gt($updatedAt)) {
            $updatedAt = $variant->updated_at;
        }

        return $this->toIso8601($updatedAt);
    }

    private function toIso8601($date): string
    {
        return Carbon::parse($date)->timezone(config('torob.timezone'))->toIso8601String();
    }
}

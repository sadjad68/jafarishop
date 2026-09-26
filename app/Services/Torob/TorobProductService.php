<?php

namespace App\Services\Torob;

use Illuminate\Support\Facades\DB;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductVariant;

class TorobProductService
{
    public function __construct(
        private readonly TorobProductFormatter $formatter,

    ) {
    }

    public function paginated(int $page, string $sort): array
    {
        $perPage = config('torob.per_page');
        $total = $this->countEntries();
        $maxPages = max(1, (int) ceil($total / $perPage));

        if ($page > $maxPages) {
            return $this->buildResponse($page, $total, $maxPages, []);
        }

        return $this->buildResponse(
            $page,
            $total,
            $maxPages,
            $this->fetchEntries($page, $perPage, $sort)
        );
    }

    public function byPageUrls(array $pageUrls): array
    {
        $products = [];
        foreach ($pageUrls as $pageUrl) {
            $products = array_merge($products, $this->findByPageUrl($pageUrl));
        }

        $count = count($products);

        return $this->buildResponse(1, $count, 1, $products);
    }

    public function byPageUniques(array $pageUniques): array
    {
        $products = [];
        foreach ($pageUniques as $pageUnique) {
            $formatted = $this->findByPageUnique($pageUnique);
            if ($formatted) {
                $products[] = $formatted;
            }
        }

        $count = count($products);

        return $this->buildResponse(1, $count, 1, $products);
    }

    private function buildResponse(int $currentPage, int $total, int $maxPages, array $products): array
    {
        return TorobApiResponse::make($currentPage, $total, $maxPages, $products);
    }

    private function countEntries(): int
    {
        $withoutVariants = Product::query()
            ->active()
            ->whereDoesntHave('variants')
            ->count();

        $withVariants = ProductVariant::query()
            ->whereHas('product', fn ($q) => $q->active())
            ->count();

        return $withoutVariants + $withVariants;
    }

    private function fetchEntries(int $page, int $perPage, string $sort): array
    {
        $sortColumn = $sort === 'date_updated_desc' ? 'sort_date_updated' : 'sort_date_added';
        $offset = ($page - 1) * $perPage;

        $withoutVariants = DB::table('products as p')
            ->selectRaw('p.id as product_id, NULL as variant_id, p.created_at as sort_date_added, p.updated_at as sort_date_updated')
            ->where('p.active', 1)
            ->whereNull('p.deleted_at')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('product_variants as pv')
                    ->whereColumn('pv.product_id', 'p.id')
                    ->whereNull('pv.deleted_at');
            });

        $withVariants = DB::table('products as p')
            ->join('product_variants as v', 'v.product_id', '=', 'p.id')
            ->selectRaw('p.id as product_id, v.id as variant_id, COALESCE(v.created_at, p.created_at) as sort_date_added, GREATEST(p.updated_at, COALESCE(v.updated_at, p.updated_at)) as sort_date_updated')
            ->where('p.active', 1)
            ->whereNull('p.deleted_at')
            ->whereNull('v.deleted_at');

        $rows = DB::query()
            ->fromSub($withoutVariants->unionAll($withVariants), 'entries')
            ->orderByDesc($sortColumn)
            ->orderByDesc('product_id')
            ->orderByDesc('variant_id')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        return $this->formatRows($rows);
    }

    private function formatRows($rows): array
    {
        if ($rows->isEmpty()) {
            return [];
        }

        $productIds = $rows->pluck('product_id')->unique()->values()->all();
        $variantIds = $rows->pluck('variant_id')->filter()->unique()->values()->all();

        $products = Product::query()
            ->active()
            ->with(['categories', 'images', 'specification_values.parent'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $variants = collect();
        if ($variantIds !== []) {
            $variants = ProductVariant::query()
                ->whereIn('id', $variantIds)
                ->with(['specifications.parent', 'images'])
                ->get()
                ->keyBy('id');
        }

        $formatted = [];
        foreach ($rows as $row) {
            $product = $products->get($row->product_id);
            if (!$product) {
                continue;
            }
            $variant = $row->variant_id ? $variants->get($row->variant_id) : null;
            $formatted[] = $this->formatter->formatProduct($product, $variant);
        }

        return $formatted;
    }

    private function findByPageUrl(string $pageUrl): array
    {
        $slug = $this->extractProductSlug($pageUrl);
        if (!$slug) {
            return [];
        }

        $product = Product::query()
            ->active()
            ->where('url', $slug)
            ->with([
                'categories',
                'images',
                'specification_values.parent',
                'variants.specifications.parent',
                'variants.images',
            ])
            ->first();

        if (!$product) {
            return [];
        }

        $activeVariants = $product->variants;
        $pageUrlAbsolute = \App\Library\SiteUrl::product($product);

        if (!$this->formatter->matchesPageUrl($pageUrl, $pageUrlAbsolute)) {
            return [];
        }

        $variantId = $this->extractVariantId($pageUrl);
        if ($variantId) {
            $variant = $activeVariants->firstWhere('id', $variantId);
            return $variant ? [$this->formatter->formatProduct($product, $variant)] : [];
        }

        if ($activeVariants->isEmpty()) {
            return [$this->formatter->formatProduct($product)];
        }

        return $activeVariants
            ->map(fn (ProductVariant $variant) => $this->formatter->formatProduct($product, $variant))
            ->values()
            ->all();
    }

    public function findByPageUnique(string $pageUnique): ?array
    {
        $parsed = $this->formatter->parsePageUnique($pageUnique);
        if (!$parsed) {
            return null;
        }

        $product = Product::query()
            ->active()
            ->with([
                'categories',
                'images',
                'specification_values.parent',
                'variants.specifications.parent',
                'variants.images',
            ])
            ->find($parsed['product_id']);

        if (!$product) {
            return null;
        }

        if ($parsed['variant_id']) {
            $variant = $product->variants->firstWhere('id', $parsed['variant_id']);

            return $variant ? $this->formatter->formatProduct($product, $variant) : null;
        }

        if ($product->variants->isNotEmpty()) {
            return null;
        }

        return $this->formatter->formatProduct($product);
    }

    private function extractProductSlug(string $pageUrl): ?string
    {
        $path = parse_url($pageUrl, PHP_URL_PATH) ?: $pageUrl;
        $path = trim($path, '/');

        if (preg_match('#(?:^|/)product/([^/]+)/?$#i', $path, $matches)) {
            return urldecode($matches[1]);
        }

        return null;
    }

    private function extractVariantId(string $pageUrl): ?int
    {
        $query = parse_url($pageUrl, PHP_URL_QUERY);
        if (!$query) {
            return null;
        }

        parse_str($query, $params);
        if (!empty($params['variant']) && is_numeric($params['variant'])) {
            return (int) $params['variant'];
        }

        return null;
    }
}

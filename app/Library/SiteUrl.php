<?php

namespace App\Library;

use App\Modules\Blog\Entities\BlogCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SiteUrl
{
    private static array $productCategories = [];

    private static array $productOldIds = [];

    private static bool $productOldIdsLoaded = false;

    private static array $categoryMeta = [];

    private static bool $categoryMetaLoaded = false;

    private static array $blogCategories = [];

    private static array $blogCategoryTypes = [];

    public static function product($product, bool $absolute = true, array $extra = []): string
    {
        $oldId = self::productOldId($product);
        if ($oldId) {
            $path = '/product/' . $oldId;
            if ($extra !== []) {
                $path .= '?' . http_build_query($extra);
            }

            return $absolute ? url($path) : $path;
        }

        $slug = self::slug($product);
        if ($slug === '') {
            return '#';
        }

        return route('product.detail', array_merge([
            'category' => self::productCategorySlug($product, $slug),
            'url' => $slug,
        ], $extra), $absolute);
    }

    public static function brand($brand, bool $absolute = true): string
    {
        $id = self::entityId($brand);
        if (!$id) {
            return '#';
        }

        return route('brand.detail', ['url' => $id], $absolute);
    }

    public static function blog($blog, bool $absolute = true): string
    {
        $slug = self::slug($blog);
        if ($slug === '') {
            return '#';
        }

        if (self::isVideoBlog($blog, $slug)) {
            return route('blog.video', ['url' => $slug], $absolute);
        }

        return route('blog.detail', [
            'category' => self::blogCategorySlug($blog, $slug),
            'url' => $slug,
        ], $absolute);
    }


    public static function category($category, bool $absolute = true): string
    {
        $meta = self::categoryMeta($category);
        if ($meta && $meta['old_id']) {
            $path = ($meta['parent_id'] ? '/sub-category/' : '/category/') . $meta['old_id'];

            return $absolute ? url($path) : $path;
        }

        $slug = self::slug($category);
        if ($slug === '') {
            return '#';
        }

        if ($meta && $meta['parent_id'] === null) {
            return route('category.detail', ['url' => $slug], $absolute);
        }

        return route('category.listing', ['url' => $slug], $absolute);
    }

    public static function named(string $routeName, $entity, bool $absolute = true): string
    {
        if ($routeName === 'category.detail') {
            return self::category($entity, $absolute);
        }
        if ($routeName === 'brand.detail') {
            return self::brand($entity, $absolute);
        }

        return route($routeName, ['url' => self::slug($entity)], $absolute);
    }

    private static function productCategorySlug($product, string $slug): string
    {
        $fromRelation = self::relatedUrl($product, 'categories');
        if ($fromRelation) {
            return $fromRelation;
        }

        if (array_key_exists($slug, self::$productCategories)) {
            return self::$productCategories[$slug];
        }

        $categoryUrl = DB::table('products')
            ->join('product_category_product', 'products.id', '=', 'product_category_product.product_id')
            ->join('product_categories', 'product_categories.id', '=', 'product_category_product.product_category_id')
            ->where('products.url', $slug)
            ->whereNull('products.deleted_at')
            ->whereNull('product_categories.deleted_at')
            ->orderBy('product_categories.id')
            ->value('product_categories.url');

        return self::$productCategories[$slug] = $categoryUrl ?: 'item';
    }

    private static function blogCategorySlug($blog, string $slug): string
    {
        $fromRelation = self::relatedUrl($blog, 'category');
        if ($fromRelation) {
            return $fromRelation;
        }

        self::resolveBlogCategory($slug);

        return self::$blogCategories[$slug] ?: 'article';
    }

    private static function isVideoBlog($blog, string $slug): bool
    {
        if (is_object($blog) && method_exists($blog, 'relationLoaded') && $blog->relationLoaded('category')) {
            $category = $blog->category;
            if ($category) {
                return ($category->type ?? BlogCategory::TYPE_TEXT) === BlogCategory::TYPE_VIDEO;
            }
        }

        self::resolveBlogCategory($slug);

        return (self::$blogCategoryTypes[$slug] ?? BlogCategory::TYPE_TEXT) === BlogCategory::TYPE_VIDEO;
    }

    private static function resolveBlogCategory(string $slug): void
    {
        if (array_key_exists($slug, self::$blogCategories)) {
            return;
        }

        $row = DB::table('blogs')
            ->join('blog_categories', 'blog_categories.id', '=', 'blogs.parent_id')
            ->where('blogs.url', $slug)
            ->whereNull('blogs.deleted_at')
            ->first(['blog_categories.url', 'blog_categories.type']);

        self::$blogCategories[$slug] = $row?->url ?? null;
        self::$blogCategoryTypes[$slug] = $row?->type ?? BlogCategory::TYPE_TEXT;
    }

    private static function relatedUrl($model, string $relation): ?string
    {
        if (!is_object($model) || !method_exists($model, 'relationLoaded') || !$model->relationLoaded($relation)) {
            return null;
        }

        $related = $model->{$relation};
        if ($related instanceof \Illuminate\Support\Collection) {
            $url = $related->first()->url ?? null;
            return $url ? (string) $url : null;
        }

        $url = $related->url ?? null;
        return $url ? (string) $url : null;
    }

    private static function productOldId($product): ?int
    {
        if (is_object($product) && method_exists($product, 'getAttributes') && array_key_exists('old_id', $product->getAttributes())) {
            $value = $product->getAttributes()['old_id'];

            return $value ? (int) $value : null;
        }

        $id = self::entityId($product);
        if (!$id) {
            return null;
        }

        if (!self::$productOldIdsLoaded) {
            self::$productOldIdsLoaded = true;
            if (Schema::hasTable('products') && Schema::hasColumn('products', 'old_id')) {
                self::$productOldIds = DB::table('products')
                    ->whereNotNull('old_id')
                    ->pluck('old_id', 'id')
                    ->map(function ($value) {
                        return (int) $value;
                    })
                    ->all();
            }
        }

        return self::$productOldIds[$id] ?? null;
    }

    private static function categoryMeta($category): ?array
    {
        if (is_object($category) && method_exists($category, 'getAttributes')) {
            $attributes = $category->getAttributes();
            if (array_key_exists('old_id', $attributes) && array_key_exists('parent_id', $attributes)) {
                return [
                    'old_id' => $attributes['old_id'] ? (int) $attributes['old_id'] : null,
                    'parent_id' => $attributes['parent_id'] ? (int) $attributes['parent_id'] : null,
                ];
            }
        }

        $id = self::entityId($category);
        if (!$id) {
            return null;
        }

        if (!self::$categoryMetaLoaded) {
            self::$categoryMetaLoaded = true;
            if (Schema::hasTable('product_categories') && Schema::hasColumn('product_categories', 'old_id')) {
                $rows = DB::table('product_categories')->get(['id', 'old_id', 'parent_id']);
                foreach ($rows as $row) {
                    self::$categoryMeta[(int) $row->id] = [
                        'old_id' => $row->old_id ? (int) $row->old_id : null,
                        'parent_id' => $row->parent_id ? (int) $row->parent_id : null,
                    ];
                }
            }
        }

        return self::$categoryMeta[$id] ?? null;
    }

    private static function entityId($entity): ?int
    {
        $id = null;
        if (is_object($entity)) {
            $id = $entity->id ?? null;
        } elseif (is_array($entity)) {
            $id = $entity['id'] ?? null;
        }

        return $id ? (int) $id : null;
    }

    private static function slug($entity): string
    {
        if (is_string($entity)) {
            return trim($entity, '/');
        }
        if (is_array($entity)) {
            return trim((string) ($entity['url'] ?? ''), '/');
        }
        if (is_object($entity)) {
            return trim((string) ($entity->url ?? ''), '/');
        }

        return '';
    }
}

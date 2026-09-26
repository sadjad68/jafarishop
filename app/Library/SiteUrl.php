<?php

namespace App\Library;

use App\Modules\Blog\Entities\BlogCategory;
use Illuminate\Support\Facades\DB;

class SiteUrl
{
    private static array $productCategories = [];

    private static array $blogCategories = [];

    private static array $blogCategoryTypes = [];

    public static function product($product, bool $absolute = true, array $extra = []): string
    {
        $slug = self::slug($product);
        if ($slug === '') {
            return '#';
        }

        return route('product.detail', array_merge([
            'category' => self::productCategorySlug($product, $slug),
            'url' => $slug,
        ], $extra), $absolute);
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
        $slug = self::slug($category);
        if ($slug === '') {
            return '#';
        }

        return route('category.listing', ['url' => $slug], $absolute);
    }

    public static function named(string $routeName, $entity, bool $absolute = true): string
    {
        if ($routeName === 'category.detail') {
            return self::category($entity, $absolute);
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

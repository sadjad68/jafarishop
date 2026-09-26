<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap-static-pages') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
    </sitemap>
    @if(count($services) > 0)
        <sitemap>
            <loc>{{ route('sitemap-services') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
    @if(count($blogs) > 0)
        <sitemap>
            <loc>{{ route('sitemap-blogs') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
    @if(count($blog_categories) > 0)
        <sitemap>
            <loc>{{ route('sitemap-blog-categories') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
    @if(count($products) > 0)
        @php
            $total = \App\Modules\Product\Services\ProductService::findAll(['active' => true,'select'=>'url']);
            $count = $total->count();
            $chunks = ceil($count / 2000);
        @endphp

        @for ($i = 1; $i <= $chunks; $i++)
            <sitemap>
                <loc>{{ route('sitemap-products-chunk', ['chunk' => $i]) }}</loc>
                <lastmod>{{ now()->toDateString() }}</lastmod>
            </sitemap>
        @endfor
    @endif
    @if(count($product_categories) > 0)
        <sitemap>
            <loc>{{ route('sitemap-product-categories') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
    @if(count($brands) > 0)
        <sitemap>
            <loc>{{ route('sitemap-brands') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
    @if(count($tags) > 0)
        <sitemap>
            <loc>{{ route('sitemap-tags') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
    @if(count($pages) > 0)
        <sitemap>
            <loc>{{ route('sitemap-pages') }}</loc>
            <lastmod>{{ now()->toDateString() }}</lastmod>
        </sitemap>
    @endif
</sitemapindex>

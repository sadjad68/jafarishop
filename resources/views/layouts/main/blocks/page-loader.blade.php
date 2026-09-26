@php
    $routeName = optional(request()->route())->getName() ?? '';
    $loaderLayout = 'simple';

    if ($routeName === 'index') {
        $loaderLayout = 'home';
    } elseif (in_array($routeName, ['product.detail', 'package.detail', 'portfolio.detail', 'service.detail'], true)) {
        $loaderLayout = 'pdp';
    } elseif (in_array($routeName, ['product.get-all', 'product.get-discounted-list', 'category.detail', 'brand.detail', 'search.detail'], true)) {
        $loaderLayout = 'plp';
    } elseif (in_array($routeName, [
        'category.list',
        'brand.list',
        'blog.category-list',
        'blog.list',
        'gallery.category',
        'gallery.list',
        'portfolio.list',
        'package.list',
        'service.list',
        'tag.list',
    ], true)) {
        $loaderLayout = 'cards';
    } elseif (in_array($routeName, ['blog.detail', 'page.detail', 'us.about', 'us.terms', 'us.contact', 'tag.detail'], true)) {
        $loaderLayout = 'article';
    } elseif (strpos($routeName, 'basket.') === 0) {
        $loaderLayout = in_array($routeName, ['basket.success', 'basket.failed', 'basket.order-images'], true)
            ? 'simple'
            : 'cart';
    } elseif (strpos($routeName, 'auth.') === 0) {
        $loaderLayout = 'auth';
    } elseif (strpos($routeName, 'panel.') === 0) {
        $loaderLayout = 'panel';
    }
@endphp
<div id="site-page-loader" class="site-page-loader" data-layout="{{ $loaderLayout }}" role="status" aria-live="polite" aria-busy="true">
    <span class="visually-hidden">صفحه در حال آماده‌سازی است</span>
    <div class="site-page-loader__frame" aria-hidden="true">
        <div class="site-page-loader__header">
            <span class="site-page-loader__bone site-page-loader__logo"></span>
            <span class="site-page-loader__nav">
                <span class="site-page-loader__bone site-page-loader__chip"></span>
                <span class="site-page-loader__bone site-page-loader__chip"></span>
                <span class="site-page-loader__bone site-page-loader__chip"></span>
                <span class="site-page-loader__bone site-page-loader__chip"></span>
            </span>
            <span class="site-page-loader__tools">
                <span class="site-page-loader__bone site-page-loader__search"></span>
                <span class="site-page-loader__bone site-page-loader__icon"></span>
                <span class="site-page-loader__bone site-page-loader__icon"></span>
            </span>
        </div>
        <div class="site-page-loader__main site-page-loader__main--{{ $loaderLayout }}">
            @include('layouts.main.blocks.page-loader.' . $loaderLayout)
        </div>
    </div>
</div>
<noscript>
    <style>
        .site-page-loader { display: none !important; }
        html.is-site-loading,
        html.is-site-loading body { overflow: auto !important; }
    </style>
</noscript>

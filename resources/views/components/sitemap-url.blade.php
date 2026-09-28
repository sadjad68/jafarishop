@props(['route', 'entity', 'sitemap'])

@php
    if ($route === 'product.detail') {
        $url = \App\Library\SiteUrl::product($entity);
    } elseif ($route === 'blog.detail') {
        $url = \App\Library\SiteUrl::blog($entity);
    } elseif ($route === 'category.detail') {
        $url = \App\Library\SiteUrl::category($entity);
    } elseif ($route === 'brand.detail') {
        $url = \App\Library\SiteUrl::brand($entity);
    } else {
        $url = route($route, $entity->url ?? '');
    }
    $shouldDisplay =
        isset($entity->seoIndex) &&
        $entity->seoIndex == 0 &&
        !\App\Library\SiteHelper::checkRedirect($url);
    $sitemap_config = \App\Modules\Setting\Entities\Sitemap::where('key', $route)->first();
@endphp

@if($shouldDisplay)
    <url>
        <loc>{{ $url }}</loc>
        <changefreq>{{ $sitemap_config->change_frequency ?? 'daily' }}</changefreq>
        <priority>{{ $sitemap_config->priority ?? '0.8' }}</priority>
    </url>
@endif

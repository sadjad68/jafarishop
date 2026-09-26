<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($sitemaps as $sitemap)
        @php
            $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName($sitemap['key']);
            $routes = isset($route) && @$route->parameterNames()[0] != "url"
                ? ($sitemap['key'] == 'index' ? "" : trim(str_replace(url('/'), "", route($sitemap['key'])), '/'))
                : null;
            $seo_metas = isset($routes) ? \App\Modules\Seo\Services\SeoService::findUrlStatic($routes) : null;
        @endphp
        @if(isset($route) && @$route->parameterNames()[0] != "url")
            @if(!\App\Library\SiteHelper::checkRedirect(route($sitemap['key'])) && @$seo_metas['noindex'] == 0)
                <url>
                    <loc>{{ route($sitemap['key']) }}</loc>
                    <changefreq>{{$sitemap['change_frequency']}}</changefreq>
                    <priority>{{$sitemap['priority']}}</priority>
                </url>
            @endif
        @endif
    @endforeach
</urlset>

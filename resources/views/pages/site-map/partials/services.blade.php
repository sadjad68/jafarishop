<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($services as $service)
        <x-sitemap-url route="service.detail" :entity="$service"/>
    @endforeach
</urlset>

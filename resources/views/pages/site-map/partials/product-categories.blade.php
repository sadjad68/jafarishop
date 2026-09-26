<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($product_categories as $product_category)
        <x-sitemap-url route="category.detail" :entity="$product_category"/>
    @endforeach
</urlset>

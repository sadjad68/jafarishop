<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($blog_categories as $blog_category)
        <x-sitemap-url route="blog.list" :entity="$blog_category"/>
    @endforeach
</urlset>

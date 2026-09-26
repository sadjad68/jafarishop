@php
    $category = $category ?? $product_category ?? null;
    $reveal = $reveal ?? false;
@endphp
<li class="cat-card"@if($reveal) data-reveal="zoom"@endif>
    <a href="{{ \App\Library\SiteUrl::category($category) }}" class="cat-card__link">
        <span class="cat-card__media">
            <img src="{{ $category->getImage('big') }}"
                 width="280"
                 height="280"
                 loading="lazy"
                 alt="{{ $category['title'] }}"
                 title="{{ $category['title'] }}">
        </span>
        <span class="cat-card__name">{{ $category['title'] }}</span>
    </a>
</li>

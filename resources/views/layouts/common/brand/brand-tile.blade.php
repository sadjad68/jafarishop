@php
    $brandHref = filled($brand['id'] ?? null)
        ? \App\Library\SiteUrl::brand($brand)
        : null;
@endphp
@if($brandHref)
<a href="{{ $brandHref }}" class="brand-tile">
@else
<span class="brand-tile">
@endif
    <span class="brand-tile__logo">
        <img src="{{ $brand->item_image }}"
             alt=""
             width="130"
             height="55"
             loading="lazy"
             aria-hidden="true">
    </span>
    <span class="brand-tile__name">{{ $brand['title'] }}</span>
@if($brandHref)
</a>
@else
</span>
@endif

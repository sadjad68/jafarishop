{{--
    Shared flat promo badge used by the discounted-products strip and the
    tag-based offer strips. Purely presentational — no business logic.
    Params: t1_badge_title, t1_badge_link, t1_badge_icon (optional)
--}}
<div class="offer-badge" data-reveal>
    @if(!empty($t1_badge_icon))
        <img src="{{ $t1_badge_icon }}" class="offer-badge__icon" alt="{{ $t1_badge_title }}" title="{{ $t1_badge_title }}" loading="lazy">
    @endif
    <p class="offer-badge__title">{{ $t1_badge_title }}</p>
    <a href="{{ $t1_badge_link }}" class="offer-badge__cta h-rotate">
        مشاهده همه
        <i class="bi bi-chevron-left d-flex main-icon dynamic-color" aria-hidden="true"></i>
    </a>
</div>

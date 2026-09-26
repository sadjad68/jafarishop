@if(($variant ?? 'skeleton') === 'skeleton')
<div class="plp-loading-skeleton" role="status" aria-live="polite" aria-label="در حال بارگذاری محصولات">
    <p class="plp-loading-skeleton__label">
        <span class="plp-loading-skeleton__icon" aria-hidden="true"></span>
        در حال بارگذاری محصولات
    </p>
    <div class="plp-loading-skeleton__grid">
        @for ($i = 0; $i < 8; $i++)
            <div class="plp-skeleton-card" aria-hidden="true">
                <div class="plp-skeleton-card__media plp-skeleton-shine"></div>
                <div class="plp-skeleton-card__body">
                    <div class="plp-skeleton-card__line plp-skeleton-card__line--title plp-skeleton-shine"></div>
                    <div class="plp-skeleton-card__line plp-skeleton-card__line--title-short plp-skeleton-shine"></div>
                    <div class="plp-skeleton-card__line plp-skeleton-card__line--price plp-skeleton-shine"></div>
                </div>
            </div>
        @endfor
    </div>
    <span class="visually-hidden">در حال بارگذاری محصولات</span>
</div>
@else
<div class="plp-loading-more" role="status" aria-live="polite" aria-label="در حال بارگذاری محصولات بیشتر">
    <div class="plp-loading-more__ring" aria-hidden="true">
        <span class="plp-loading-more__ring-track"></span>
        <span class="plp-loading-more__ring-arc"></span>
    </div>
    <p class="plp-loading-more__text">
        در حال بارگذاری
        <span class="plp-loading-more__dots" aria-hidden="true">
            <span></span><span></span><span></span>
        </span>
    </p>
    <span class="visually-hidden">در حال بارگذاری محصولات بیشتر</span>
</div>
@endif

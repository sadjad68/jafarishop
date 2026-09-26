<div class="sk-sample-skeleton sk-sample-grid"
     v-if="loading && isFilter && page === 1"
     role="status"
     aria-live="polite"
     aria-label="در حال بارگذاری نمونه کارها">
    @for ($i = 0; $i < 6; $i++)
        <div class="sk-sample-card sk-sample-skeleton__card" aria-hidden="true">
            <span class="sk-sample-skeleton__media sk-skeleton-shine"></span>
            <span class="sk-sample-skeleton__name">
                <span class="sk-sample-skeleton__line sk-skeleton-shine"></span>
                <span class="sk-sample-skeleton__chevron sk-skeleton-shine"></span>
            </span>
        </div>
    @endfor
    <span class="visually-hidden">در حال بارگذاری نمونه کارها</span>
</div>
<div class="sk-sample-loading-more"
     v-else-if="loading"
     role="status"
     aria-live="polite"
     aria-label="در حال بارگذاری نمونه کارهای بیشتر">
    <div class="sk-sample-loading-more__ring" aria-hidden="true">
        <span class="sk-sample-loading-more__track"></span>
        <span class="sk-sample-loading-more__arc"></span>
    </div>
    <p class="sk-sample-loading-more__text">
        در حال بارگذاری
        <span class="sk-sample-loading-more__dots" aria-hidden="true">
            <span></span><span></span><span></span>
        </span>
    </p>
</div>

@php($showNotificationButtons = (int) (@$settings['active_notifications'] ?? 0) === 1)

@if (count(@$product->variants) > 0 || intval($product['stock']) != 0)
    @if (count(@$product->variants) > 0 || $product['final_price'] != 0)
        <template v-if="isAvailable && showStickyPurchaseBar">
            <div class="pdp-sticky-bar pdp-sticky-bar--dock" role="region" aria-label="افزودن سریع به سبد خرید">
                <div class="pdp-sticky-bar__inner">
                    @include('pages.product-detail._partials.components.sticky-support')
                    <div class="pdp-sticky-bar__toolbar">
                        <div class="pdp-sticky-bar__cta">
                            @include('pages.product-detail._partials.components.cart-btn', ['sticky' => true])
                        </div>
                        <div class="pdp-sticky-bar__qty">
                            @include('pages.product-detail._partials.components.counter')
                        </div>
                        <div class="pdp-sticky-bar__price">
                            @include('pages.product-detail._partials.components.price', ['sticky' => true])
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template v-else-if="showStickyPurchaseBar">
            <div class="pdp-sticky-bar pdp-sticky-bar--dock" v-if="needsVariantSelection">
                <div class="pdp-sticky-bar__inner">
                    @include('pages.product-detail._partials.components.sticky-support')
                    <button type="button" @click="scrollToVariants()"
                        class="btn pdp-add-btn pdp-add-btn--select w-100 d-flex align-items-center justify-content-center gap-2 text-wrap">
                        <i class="bi bi-sliders d-flex flex-shrink-0"></i>
                        ابتدا متغیر مدنظر خود را انتخاب کنید
                    </button>
                </div>
            </div>
            <div class="pdp-sticky-bar pdp-sticky-bar--stacked pdp-sticky-bar--dock" v-else-if="isUnavailable">
                <div class="pdp-sticky-bar__inner">
                    @include('pages.product-detail._partials.components.sticky-support')
                    <span class="pdp-purchase__stock pdp-purchase__stock--out text-center w-100">ناموجود</span>
                    @if ($showNotificationButtons)
                        <button type="button" class="btn btn-primary btn-became-available w-100"
                            @click="handleNotificationClick('available')">
                            <span v-if="isSubscribed('available')">دیگر لازم نیست خبرم کنید</span>
                            <span v-else>موجود شد خبرم کن</span>
                        </button>
                    @endif
                </div>
            </div>
            <div class="pdp-sticky-bar pdp-sticky-bar--dock" v-else>
                <div class="pdp-sticky-bar__inner">
                    @include('pages.product-detail._partials.components.sticky-support')
                    <a href="tel:{{ $settings['main_phone_number'] }}"
                        class="btn pdp-add-btn w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-telephone d-flex"></i>
                        تماس بگیرید
                    </a>
                </div>
            </div>
        </template>
    @else
        <div class="pdp-sticky-bar pdp-sticky-bar--dock" v-if="showStickyPurchaseBar">
            <div class="pdp-sticky-bar__inner">
                @include('pages.product-detail._partials.components.sticky-support')
                <a href="tel:{{ $settings['main_phone_number'] }}"
                    class="btn pdp-add-btn w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-telephone d-flex"></i>
                    تماس بگیرید
                </a>
            </div>
        </div>
    @endif
@else
    <div class="pdp-sticky-bar pdp-sticky-bar--stacked pdp-sticky-bar--dock" v-if="showStickyPurchaseBar">
        <div class="pdp-sticky-bar__inner">
            @include('pages.product-detail._partials.components.sticky-support')
            <span class="pdp-purchase__stock pdp-purchase__stock--out text-center w-100">ناموجود</span>
            @if ($showNotificationButtons)
                <button type="button" class="btn btn-primary btn-became-available w-100"
                    @click="openNotify('available')">
                    موجود شد خبرم کن
                </button>
            @endif
        </div>
    </div>
@endif

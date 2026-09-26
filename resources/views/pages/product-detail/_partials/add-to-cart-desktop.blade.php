@php($showNotificationButtons = (int) (@$settings['active_notifications'] ?? 0) === 1)
@if (count(@$product->variants) > 0 || intval($product['stock']) != 0)
    @if (count(@$product->variants) > 0 || $product['final_price'] != 0)
        {{-- موجود و قابل خرید --}}
        <div v-if="isAvailable" class="pdp-purchase" id="pdp-purchase-anchor">
            <div class="pdp-purchase__price">
                @include('pages.product-detail._partials.components.price')
            </div>

            @include('pages.product-detail._partials.components.snapp-pay')

            <div class="pdp-purchase__actions">
                <div class="pdp-purchase__qty">
                    @include('pages.product-detail._partials.components.counter')
                </div>
                <div class="pdp-purchase__cta-wrap">
                    @if ($showNotificationButtons)
                        <div class="auction d-none d-md-block btn p-0 flex-shrink-0" v-if="showAuctionBell()"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            :data-bs-title="isSubscribed('discount') ? 'لغو اطلاع‌رسانی حراج' : 'حراج شد خبرم کن'">
                            <button type="button" class="btn btn-primary btn-auction"
                                @click="handleNotificationClick('discount')">
                                <img v-if="isSubscribed('discount')" width="25"
                                    src="{{ asset('assets/site/images/bell-2.svg') }}" alt="فعال">
                                <img v-else width="25" src="{{ asset('assets/site/images/bell-1.svg') }}"
                                    alt="غیرفعال">
                            </button>
                        </div>
                    @endif
                    <div class="pdp-purchase__cta">
                        @include('pages.product-detail._partials.components.cart-btn')
                    </div>
                </div>
            </div>

            @if (@$settings['show_share_button'] == 1 && in_array(@$settings['share_button_display_type'], ['normal', 'both']))
                <div class="pdp-purchase__social">
                    @include('pages.product-detail._partials.components.btn-pm-social')
                </div>
            @endif
        </div>

        {{-- انتخاب متغیر، ناموجود یا تماس بگیرید --}}
        <div v-else class="pdp-purchase pdp-purchase--unavailable">
            <div v-if="needsVariantSelection" class="pdp-purchase__wait">
                <p class="pdp-purchase__hint">ابتدا متغیر مدنظر خود را انتخاب کنید</p>
                <button type="button" class="btn pdp-add-btn" @click="scrollToVariants()">
                    <i class="bi bi-sliders d-flex" aria-hidden="true"></i>
                    انتخاب گزینه
                </button>
            </div>
            <div v-else-if="isUnavailable" class="d-flex flex-column align-items-center gap-3 w-100">
                <span class="pdp-purchase__stock pdp-purchase__stock--out">ناموجود</span>
                @if ($showNotificationButtons)
                    <button type="button" class="btn btn-primary btn-became-available w-100"
                        @click="handleNotificationClick('available')">
                        <span v-if="isSubscribed('available')">دیگر لازم نیست خبرم کنید</span>
                        <span v-else>موجود شد خبرم کن</span>
                    </button>
                @endif
            </div>
            <div v-else class="w-100">
                <a href="tel:{{ $settings['main_phone_number'] }}"
                    class="btn pdp-add-btn d-flex align-items-center justify-content-between w-100">
                    <span>تماس بگیرید</span>
                    <span dir="ltr" class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone d-flex"></i>
                        {{ $settings['main_phone_number'] }}
                    </span>
                </a>
            </div>

            @if (@$settings['show_share_button'] == 1 && in_array(@$settings['share_button_display_type'], ['normal', 'both']))
                <div class="pdp-purchase__social w-100">
                    @include('pages.product-detail._partials.components.btn-pm-social')
                </div>
            @endif
        </div>
    @else
        {{-- قیمت صفر — تماس --}}
        <div class="pdp-purchase pdp-purchase--contact">
            <a href="tel:{{ $settings['main_phone_number'] }}"
                class="btn pdp-add-btn d-flex align-items-center justify-content-between w-100">
                <span>تماس بگیرید</span>
                <span dir="ltr" class="d-flex align-items-center gap-2">
                    <i class="bi bi-telephone d-flex"></i>
                    {{ $settings['main_phone_number'] }}
                </span>
            </a>
            @if (@$settings['show_share_button'] == 1 && in_array(@$settings['share_button_display_type'], ['normal', 'both']))
                <div class="pdp-purchase__social">
                    @include('pages.product-detail._partials.components.btn-pm-social')
                </div>
            @endif
        </div>
    @endif
@else
    {{-- موجودی صفر --}}
    <div class="pdp-purchase pdp-purchase--unavailable">
        <span class="pdp-purchase__stock pdp-purchase__stock--out">ناموجود</span>
        @if ($showNotificationButtons)
            <button type="button" class="btn btn-primary btn-became-available w-100"
                @click="handleNotificationClick('available')">
                <span v-if="isSubscribed('available')">دیگر لازم نیست خبرم کنید</span>
                <span v-else>موجود شد خبرم کن</span>
            </button>
        @endif
        @if (@$settings['show_share_button'] == 1 && in_array(@$settings['share_button_display_type'], ['normal', 'both']))
            <div class="pdp-purchase__social w-100">
                @include('pages.product-detail._partials.components.btn-pm-social')
            </div>
        @endif
    </div>
@endif

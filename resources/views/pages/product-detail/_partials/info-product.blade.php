@php($showNotificationButtons = (int) (@$settings['active_notifications'] ?? 0) === 1)
<div class="pdp-info">
    @if ($showNotificationButtons)
        <div class="pdp-identity">
            <div class="pdp-identity__meta">
                <div class="auction-mobile d-md-none d-inline-flex position-relative" v-if="showAuctionBell()">
                    <button type="button" class="btn btn-primary btn-auction"
                        @click="handleNotificationClick('discount')">
                        <img v-if="isSubscribed('discount')" width="25"
                            src="{{ asset('assets/site/images/bell-2.svg') }}" alt="فعال">
                        <img v-else width="25" src="{{ asset('assets/site/images/bell-1.svg') }}" alt="غیرفعال">
                    </button>
                    <span id="auctionText" class="auction-text">اطلاع رسانی</span>
                </div>
            </div>
        </div>
    @endif

    @if (count($properties) > 0)
        <div class="pdp-info__features">
            @include('pages.product-detail._partials.attributes')
        </div>
    @endif

    <div class="pdp-dock">
        <div class="pdp-info__variants pdp-info__variants--toolbar" v-if="Object.keys(selectedSpecs).length !== 0 && filteredMainSpecs.length > 1">
            <button type="button" @click="resetAllSelections" class="pdp-info__reset">
                <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                پاک کردن انتخاب‌ها
            </button>
        </div>

        <div class="pdp-info__variants" id="pdp-variant-selector" v-if="filteredMainSpecs.length > 0">
            @include('pages.product-detail._partials.components.variant-selector')
        </div>

        @if (count($properties) == 0)
            <div class="pdp-info__trust" v-if="filteredMainSpecs.length == 0">
                @include('pages.product-detail._partials.slogan-two')
            </div>
        @endif

        @mobile
            <div class="pdp-info__snapp d-lg-none" v-if="isAvailable">
                @include('pages.product-detail._partials.components.snapp-pay')
            </div>
            <div class="d-lg-none p-0 m-0" id="pdp-purchase-anchor" aria-hidden="true" style="height:1px;overflow:hidden;"></div>
            <div class="pdp-info__purchase d-lg-none">
                @include('pages.product-detail._partials.add-to-cart-mobile')
            </div>
        @else
            <div class="pdp-info__purchase d-lg-block d-none">
                @include('pages.product-detail._partials.add-to-cart-desktop')
            </div>
        @endmobile
    </div>
</div>

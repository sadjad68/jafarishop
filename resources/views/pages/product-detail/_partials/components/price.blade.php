@php($isSticky = !empty($sticky))
<div class="pdp-purchase__price-block{{ $isSticky ? ' pdp-purchase__price-block--sticky' : '' }}" v-if="displayFinalPrice != 0">
    <div class="pdp-purchase__price-main">
        <div class="pdp-purchase__meta{{ $isSticky ? '' : ' mb-2' }}">
            <span class="pdp-purchase__old-price" v-if="displayPrice != 0 && displayPrice != displayFinalPrice">
                @{{ displayPrice }}
            </span>
            <span class="pdp-purchase__discount" v-if="discountPercent != 0">
                @{{ discountPercent }}٪
            </span>
        </div>
        <div class="pdp-purchase__final-price f-number-fa">
            @if ($isSticky)
                <span class="pdp-purchase__currency" aria-hidden="true">تومان</span>
            @endif
            @{{ displayFinalPrice }}
            @unless ($isSticky)
                <img src="{{ asset('assets/site/images/toman.svg') }}" alt="تومان" width="22" height="22" />
            @endunless
        </div>
    </div>
    @unless ($isSticky)
        <span class="pdp-purchase__stock" v-if="isAvailable">
            <img src="{{ asset('assets/site/images/available.svg') }}" alt="" width="16" height="16" />
            موجود در انبار
        </span>
    @endunless
</div>

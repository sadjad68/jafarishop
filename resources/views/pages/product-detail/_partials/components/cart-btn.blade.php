@php($isSticky = !empty($sticky))
@mobile
    @if (count(@$product->variants) > 0)
        <a v-if="!selectedVariant" @click="scrollToVariants()" id="scrollLink"
            class="btn pdp-add-btn pdp-add-btn--select d-flex align-items-center justify-content-center w-100">
            <i class="bi bi-sliders d-flex"></i>
            {{ $isSticky ? 'انتخاب گزینه' : 'انتخاب گزینه' }}
        </a>

        <button v-else id="scrollLink" type="button" @click="addToBasket()"
            class="btn pdp-add-btn d-flex align-items-center justify-content-center w-100">
            <i class="bi bi-bag-plus d-flex"></i>
            {{ $isSticky ? 'افزودن به سبد خرید' : 'افزودن به سبد' }}
        </button>
    @else
        <button type="button" @click="addToBasket()"
            class="btn pdp-add-btn d-flex align-items-center justify-content-center w-100">
            <i class="bi bi-bag-plus d-flex"></i>
            {{ $isSticky ? 'افزودن به سبد خرید' : 'افزودن به سبد' }}
        </button>
    @endif
@else
    <button type="button" @click="addToBasket()"
        class="btn pdp-add-btn d-flex align-items-center justify-content-center w-100">
        <i class="bi bi-bag-plus d-flex"></i>
        افزودن به سبد خرید
    </button>
@endmobile

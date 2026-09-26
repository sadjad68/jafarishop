<aside class="cart-sidebar" v-if="priceLoading == false">
    <div class="cart-sidebar__head">
        <h2 class="cart-sidebar__title">خلاصه سفارش</h2>
    </div>
    <ul class="cart-sidebar__rows">
        <li class="cart-sidebar__row d-lg-flex" v-if="priceSum">
            <span>قیمت کالاها</span>
            <strong class="font-num-r">@{{ priceSum }}</strong>
        </li>
        <li class="cart-sidebar__row cart-sidebar__row--discount" v-if="priceDiscount">
            <span>تخفیف</span>
            <strong class="font-num-r">@{{ priceDiscount }}</strong>
        </li>
        <li class="cart-sidebar__row cart-sidebar__row--total" v-if="finalPriceSum">
            <span>مبلغ کل</span>
            <strong class="font-num-r">@{{ finalPriceSum }}</strong>
        </li>
    </ul>
    <a href="{{ route('basket.shipping') }}" class="sk-cta cart-sidebar__cta d-none d-lg-inline-flex">
        ادامه و تکمیل سفارش
        <i class="bi bi-arrow-left"></i>
    </a>
</aside>
<aside class="cart-sidebar cart-sidebar--loading" v-else>
    @include('layouts.common.loading')
</aside>

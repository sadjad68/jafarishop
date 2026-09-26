<aside class="cart-sidebar" v-if="priceLoading == false">
    <div class="cart-sidebar__head">
        <h2 class="cart-sidebar__title">خلاصه سفارش</h2>
    </div>
    <ul class="cart-sidebar__rows">
        <li class="cart-sidebar__row" v-if="priceShipping">
            <span>هزینه ارسال</span>
            <strong class="font-num-r">@{{ priceShipping }}</strong>
        </li>
        <li class="cart-sidebar__row" v-if="finalPriceSum">
            <span>مبلغ کل کالا</span>
            <strong class="font-num-r">@{{ finalPriceSum }}</strong>
        </li>
        <li class="cart-sidebar__row cart-sidebar__row--total" v-if="priceCart">
            <span>مبلغ قابل پرداخت</span>
            <strong class="font-num-r">@{{ priceCart }}</strong>
        </li>
    </ul>
    <a href="{{ route('basket.payment') }}" class="sk-cta cart-sidebar__cta d-none d-lg-inline-flex" @click.prevent="goToPayment">
        ادامه به پرداخت
        <i class="bi bi-arrow-left"></i>
    </a>
</aside>
<aside class="cart-sidebar cart-sidebar--loading" v-else>
    @include('layouts.common.loading')
</aside>

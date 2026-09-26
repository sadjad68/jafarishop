<div class="cart-shipping" v-if="this.loadingShipment == false && shippingMethods.length > 0">
    <div class="cart-shipping__head">
        <h2 class="cart-shipping__title">روش ارسال</h2>
        <p class="cart-shipping__hint">یکی از روش‌های زیر را انتخاب کنید</p>
    </div>

    <div class="cart-shipping__grid">
        <div class="cart-shipping-item" v-for="shippingMethod in shippingMethods" :key="shippingMethod.id">
            <label class="cart-radio cart-radio--block" :for="'flexCheckDefault-'+ shippingMethod.id">
                <input class="cart-radio__input" type="radio" name="shipping_method_id"
                       v-model="defaultShippingMethodId"
                       @change="setShippingMethod()"
                       :checked="shippingMethod.id == defaultShippingMethodId"
                       :id="'flexCheckDefault-'+ shippingMethod.id" :value="shippingMethod.id">
                <span class="cart-radio__mark"></span>
                <span class="cart-shipping-item__content">
                    <span class="cart-shipping-item__title">@{{ shippingMethod.title }}</span>
                    <span class="cart-shipping-item__desc" v-if="shippingMethod.description">@{{ shippingMethod.description }}</span>
                    <span class="cart-shipping-item__price font-num-r" v-if="shippingMethod.freight_balance == 0">
                        هزینه ارسال:
                        <template v-if="shippingMethod.type === 'chapar'">طبق تعرفه شرکت</template>
                        <template v-else>@{{ getShippingPriceText(shippingMethod) }}</template>
                    </span>
                    <span class="cart-shipping-item__price font-num-r" v-else>پس‌کرایه</span>
                </span>
            </label>
        </div>
    </div>
</div>
<div class="cart-card__loading" v-else-if="this.loadingShipment == true">
    @include('layouts.common.loading')
</div>

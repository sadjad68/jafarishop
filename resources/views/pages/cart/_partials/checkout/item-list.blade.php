<div class="cart-product__inner">
    <div class="cart-product__media">
        <a :href="item.product_url" target="_blank" class="cart-product__image-link">
            <img :src="item.product_image" class="cart-product__image" :alt="item.product_title" :title="item.product_title" loading="lazy">
        </a>
    </div>

    <div class="cart-product__body">
        <div class="cart-product__top">
            <a class="cart-product__title" target="_blank" :href="item.product_url">
                @{{ item.product_title }}
            </a>
            <button @click="removeCart(item.id)" type="button" class="cart-product__remove" title="حذف از سبد" aria-label="حذف از سبد">
                <i class="bi bi-trash3"></i>
            </button>
        </div>

        <div class="cart-product__specs" v-if="item.variant_id">
            <div class="cart-product__spec" v-for="specification in item.specifications">
                <span class="cart-product__spec-label">@{{ specification.parent.title }}</span>
                <span class="cart-product__spec-value">
                    <span class="cart-product__color" :style="{ backgroundColor: specification.color_code == null || specification.color_code == 'undefined' ? '#000000' : specification.color_code }" v-if="specification.parent.is_color"></span>
                    @{{ specification.title }}
                </span>
            </div>
        </div>

        <div class="cart-product__footer">
            <div class="cart-product__price">
                <span class="cart-product__price-label">قیمت</span>
                <div class="cart-product__price-values">
                    <span class="cart-product__discount font-num-r" v-if="item.percent">@{{ item.percent }}٪</span>
                    <del class="cart-product__old font-num-r" v-if="item.percent && item.product_price != 0">@{{ item.product_price }}</del>
                    <strong class="cart-product__current font-num-r">@{{ item.product_final_price }}</strong>
                </div>
            </div>

            <div class="cart-qty">
                <button type="button" class="cart-qty__btn dynamic-color" @click="decreaseValue(index)" aria-label="کاهش تعداد">
                    <i class="bi bi-dash"></i>
                </button>
                <input type="text" class="cart-qty__input font-num-r" readonly :value="item.quantity" aria-label="تعداد">
                <button type="button" class="cart-qty__btn dynamic-color" @click="increaseValue(index)" aria-label="افزایش تعداد">
                    <i class="bi bi-plus"></i>
                </button>
                <div class="cart-qty__loading" v-if="itemLoading === true">
                    @include('layouts.common.loading')
                </div>
            </div>
        </div>
    </div>
</div>

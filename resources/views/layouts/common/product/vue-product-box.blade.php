@if ($theme_provider->getValue() == 'theme1')
    @include('layouts.common.product.theme1-vue-product-box')
@else
<a :href="product.vue_url" class="product-card-link" target="_blank">
    <article class="product-card" :class="{ 'product-card--out': !(product.stock > 0) }">
        <div class="product-card__media">
            <span
                v-if="product.has_variants"
                class="product-card__badge product-card__badge--variant"
                aria-label="محصول دارای گزینه"
            >
                <i class="bi bi-sliders" aria-hidden="true"></i>
            </span>
            <img :src="product.image" class="product-card__img" :alt="product.title" :title="product.title" loading="lazy" width="300" height="300">
        </div>
        <div class="product-card__body">
            <h3 class="product-card__title">@{{ product.title }}</h3>
            <div class="price-block mt-2" v-if="product.stock > 0 && product.final_price != 0">
                <div class="price-block__prices">
                    <div class="price-block__compare" v-if="product.price != null && product.percent">
                        <span class="price-block__discount">@{{ product.percent }}٪</span>
                        <span class="price-block__old">@{{ product.price }}</span>
                    </div>
                    <div class="price-block__current">
                        <span class="price-block__amount">@{{ product.final_price }}</span>
                        <span class="price-block__currency">تومان</span>
                    </div>
                </div>
            </div>
            <div class="product-status mt-2" v-else-if="product.stock > 0 && product.final_price == 0">
                <span class="product-status__badge product-status__badge--call">تماس بگیرید</span>
            </div>
            <div class="product-status mt-2" v-else>
                <span class="product-status__badge product-status__badge--out">ناموجود</span>
            </div>
        </div>
    </article>
</a>
@endif

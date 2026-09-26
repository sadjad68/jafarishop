<a :href="product.vue_url"
   class="t1-pbox"
   :class="{ 't1-pbox--out': !(product.stock > 0) }"
   target="_blank">
    <article class="t1-pbox__inner">
        <div class="t1-pbox__media">
            <span
                v-if="product.has_variants"
                class="t1-pbox__chip t1-pbox__chip--opt"
                aria-label="محصول دارای گزینه"
            >
                <i class="bi bi-sliders" aria-hidden="true"></i>
            </span>
            <img :src="product.image"
                 class="t1-pbox__img"
                 :alt="product.title"
                 :title="product.title"
                 loading="lazy"
                 width="300"
                 height="300">
            <div class="t1-pbox__body">
                <h3 class="t1-pbox__title">@{{ product.title }}</h3>
                <div class="t1-pbox__price" v-if="product.stock > 0 && product.final_price != 0">
                    <div class="t1-pbox__compare" v-if="product.price != null && product.percent">
                        <span class="t1-pbox__off">@{{ product.percent }}٪</span>
                        <del class="t1-pbox__was">@{{ product.price }}</del>
                    </div>
                    <p class="t1-pbox__now">
                        <span class="t1-pbox__amount">@{{ product.final_price }}</span>
                        <span class="t1-pbox__unit">تومان</span>
                    </p>
                </div>
                <p class="t1-pbox__status" v-else-if="product.stock > 0 && product.final_price == 0">تماس بگیرید</p>
                <p class="t1-pbox__status" v-else>ناموجود</p>
            </div>
        </div>
    </article>
</a>

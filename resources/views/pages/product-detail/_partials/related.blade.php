@if (count($related_products) > 0)
    <section class="sk-related pdp-related" aria-labelledby="pdp-related-title">
        <div class="container">
            <div class="sk-section-head pdp-rail-head">
                <span class="sk-section-head__eyebrow">پیشنهاد ما</span>
                <h2 id="pdp-related-title" class="sk-section-head__title">محصولات مرتبط</h2>
            </div>
            <div dir="rtl" class="swiper pdp-related-swiper">
                <div class="swiper-wrapper">
                    @foreach ($related_products as $related_product)
                        <div class="swiper-slide">
                            @include('layouts.common.product.product-box', ['product' => $related_product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

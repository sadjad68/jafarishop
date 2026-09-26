@if (count($complement_products) > 0)
    <section class="sk-related pdp-related" aria-labelledby="pdp-complement-title">
        <div class="container">
            <div class="sk-section-head pdp-rail-head">
                <span class="sk-section-head__eyebrow">تکمیل خرید</span>
                <h2 id="pdp-complement-title" class="sk-section-head__title">محصولات مکمل</h2>
            </div>
            <div dir="rtl" class="swiper pdp-related-swiper">
                <div class="swiper-wrapper">
                    @foreach ($complement_products as $complement_product)
                        <div class="swiper-slide">
                            @include('layouts.common.product.product-box', ['product' => $complement_product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

@if (count($timer_products) > 0)
    <section class="offer" data-offer-swiper aria-labelledby="home-offer-title">
        <div class="container">
            <div class="offer-head" data-reveal>
                <div class="offer-head__copy">
                    <span class="offer-head__eyebrow">پیشنهاد محدود</span>
                    <h2 id="home-offer-title" class="offer-head__title">پیشنهاد شگفت‌انگیز</h2>
                </div>
                <div class="offer-head__actions">
                    <div class="offer-nav" role="group" aria-label="ورق زدن پیشنهادها">
                        <button type="button" class="offer-nav__btn offer-nav__btn--prev" aria-label="قبلی">
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="offer-nav__btn offer-nav__btn--next" aria-label="بعدی">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        </button>
                    </div>
                    <a href="{{ route('product.get-discounted-list') }}" class="offer-head__all">
                        مشاهده همه
                    </a>
                </div>
            </div>
            <div class="swiper swiper-offer-new" data-reveal>
                <div class="swiper-wrapper">
                    @foreach ($timer_products as $product)
                        <div class="swiper-slide">
                            @include('layouts.common.product.product-box', ['product' => $product, 'show_product_countdown' => true])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @include('pages.first-page._partials.timer-script')
@endif

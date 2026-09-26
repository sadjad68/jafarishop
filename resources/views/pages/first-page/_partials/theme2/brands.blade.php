@if(count($brands) > 0)
<section class="brands" data-brands-swiper aria-labelledby="home-brands-title">
    <div class="container">
        <div class="brands-head" data-reveal>
            <div class="brands-head__copy">
                <span class="brands-head__eyebrow">مجموعه برندها</span>
                <h2 id="home-brands-title" class="brands-head__title">محبوب‌ترین برندها</h2>
            </div>
            <div class="brands-head__actions">
                <div class="brands-nav" role="group" aria-label="ورق زدن برندها">
                    <button type="button" class="brands-nav__btn brands-nav__btn--prev" aria-label="قبلی">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="brands-nav__btn brands-nav__btn--next" aria-label="بعدی">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="{{ route('brand.list') }}" class="brands-head__all">
                    همه برندها
                </a>
            </div>
        </div>
        <div class="brands-tray" data-reveal>
            <div class="swiper swiper-brands">
                <div class="swiper-wrapper">
                    @foreach($brands as $brand)
                        <div class="swiper-slide">
                            @include('layouts.common.brand.brand-tile')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

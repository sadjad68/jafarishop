@if(count($blogs) > 0)
<section class="blogs" data-blogs-swiper aria-labelledby="home-blogs-title">
    <div class="container">
        <div class="blogs-head" data-reveal>
            <div class="blogs-head__copy">
                <span class="blogs-head__eyebrow">از وبلاگ</span>
                <h2 id="home-blogs-title" class="blogs-head__title">خواندنی‌ها</h2>
            </div>
            <div class="blogs-head__actions">
                <div class="blogs-nav" role="group" aria-label="ورق زدن مطالب">
                    <button type="button" class="blogs-nav__btn blogs-nav__btn--prev" aria-label="قبلی">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="blogs-nav__btn blogs-nav__btn--next" aria-label="بعدی">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="{{ route('blog.category-list') }}" class="blogs-head__all">
                    همه مطالب
                </a>
            </div>
        </div>
        <div class="swiper swiper-blogs" data-reveal>
            <div class="swiper-wrapper">
                @foreach($blogs as $blog)
                    <div class="swiper-slide">
                        @include('layouts.common.blog.blog-card', ['blog' => $blog, 'compact' => true])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

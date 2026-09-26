@if(count($blogs) > 0)
    <section class="blogs t1-section" aria-labelledby="t1-blogs-title">
        <div class="container">
            @include('pages.first-page._partials.theme1._section-head', [
                't1_eyebrow' => 'بلاگ',
                't1_title' => 'خواندنی‌ها',
                't1_center' => true,
                't1_title_id' => 't1-blogs-title',
            ])
            <div class="swiper mySwiper-blogsnew" data-reveal>
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

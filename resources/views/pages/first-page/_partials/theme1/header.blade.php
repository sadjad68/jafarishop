@php
    $banner_count = count($banners);
    $t1_hero_phone = trim((string) (@$settings['main_phone_number'] ?? ''));
    $t1_hero_chat = trim((string) (@$settings['online_support_link'] ?? ''));
    $t1_hero_chat_external = (bool) preg_match('#^https?://#i', $t1_hero_chat);
@endphp
<section class="hero">
    <div class="t1-hero">
            <div class="t1-hero__copy" data-reveal>
                @if(!empty($settings['siteName_fa']))
                    <span class="t1-hero__badge">
                        {{ $settings['siteName_fa'] }}
                    </span>
                @endif
                @if(!empty($settings['slider_title']))
                    <p class="t1-hero__title">
                        {{ $settings['slider_title'] }}
                    </p>
                @endif
                @if(isset($settings['slider_description']) && @$settings['slider_description'])
                    <p class="t1-hero__lead">
                        {!! $settings['slider_description'] !!}
                    </p>
                @endif
                <div class="t1-hero__actions">
                    @if(isset($settings['first_link']) && $settings['first_link'])
                        <a href="{{$settings['first_link']}}" class="t1-btn t1-btn--solid">
                            <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                            {{$settings['first_button']}}
                        </a>
                    @endif
                    @if(isset($settings['second_link']) && $settings['second_link'])
                        <a href="{{$settings['second_link']}}" class="t1-btn t1-btn--ghost">
                            <i class="bi bi-people" aria-hidden="true"></i>
                            {{$settings['second_button']}}
                        </a>
                    @endif
                </div>
                @if(!empty($settings['support_call_text']) || !empty($settings['online_support_text']) || (isset($settings['video_link']) && $settings['video_link']))
                <div class="t1-hero__facts">
                    @if(!empty($settings['support_call_text']))
                        <a href="{{ $t1_hero_phone !== '' ? 'tel:'.$t1_hero_phone : route('us.contact') }}"
                           class="t1-hero__fact">
                            <i class="bi bi-headset" aria-hidden="true"></i>
                            {{ $settings['support_call_text'] }}
                        </a>
                    @endif
                    @if(!empty($settings['online_support_text']))
                        <a href="{{ $t1_hero_chat !== '' ? $t1_hero_chat : route('us.contact') }}"
                           class="t1-hero__fact"
                           @if($t1_hero_chat !== '' && $t1_hero_chat_external) target="_blank" rel="noopener noreferrer" @endif>
                            <i class="bi bi-activity" aria-hidden="true"></i>
                            {{ $settings['online_support_text'] }}
                        </a>
                    @endif
                    @if(isset($settings['video_link']) && $settings['video_link'])
                        <button type="button"
                                class="t1-hero__fact t1-hero__fact-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#videoModal">
                            <i class="bi bi-play-fill" aria-hidden="true"></i>
                            با ما بیشتر آشنا شوید
                        </button>
                    @endif
                </div>
                @endif
            </div>
            @if($banner_count > 0)
                <div class="t1-hero__media" data-reveal>
                    <div class="t1-hero__frame">
                        <div id="carouselFirstPage" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover">
                            <div class="carousel-inner">
                                @foreach($banners as $banner)
                                    <div class="carousel-item @if($loop->first) active @endif">
                                        @mobile
                                            <img src="{{$banner['image_mobile']}}"
                                                 class="w-100 d-lg-none d-block hero-slide-img"
                                                 alt="{{$banner['title']}}"
                                                 title="{{$banner['title']}}"
                                                 @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                        @else
                                            <img src="{{$banner['image']}}"
                                                 class="w-100 d-lg-block d-none hero-slide-img"
                                                 alt="{{$banner['title']}}"
                                                 title="{{$banner['title']}}"
                                                 @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                        @endmobile
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if($banner_count > 1 || (isset($settings['video_link']) && $settings['video_link']))
                            <div class="t1-hero__chrome">
                                <div class="t1-hero__bar">
                                    @if(isset($settings['video_link']) && $settings['video_link'])
                                        <button type="button"
                                                class="t1-hero__play"
                                                data-bs-toggle="modal"
                                                data-bs-target="#videoModal"
                                                aria-label="پخش ویدیو">
                                            <i class="bi bi-play-fill" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                    @if($banner_count > 1)
                                        <div class="t1-hero__status">
                                            <span class="hero-counter" dir="ltr" aria-hidden="true">
                                                <span class="hero-counter__current" data-hero-counter-current>{{ sprintf('%02d', 1) }}</span>
                                                <span class="hero-counter__sep">/</span>
                                                <span class="hero-counter__total">{{ sprintf('%02d', $banner_count) }}</span>
                                            </span>
                                            <span class="hero-progress" dir="ltr" aria-hidden="true" data-hero-progress-wrap>
                                                @for($i = 0; $i < $banner_count; $i++)
                                                    <span class="hero-progress__tick @if($i === 0) is-active @endif" data-hero-tick>
                                                        <span class="hero-progress__bar" data-hero-progress></span>
                                                    </span>
                                                @endfor
                                            </span>
                                        </div>
                                        <div class="t1-hero__nav">
                                            <button class="t1-hero__nav-btn" type="button" data-bs-target="#carouselFirstPage" data-bs-slide="prev">
                                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                                <span class="visually-hidden">اسلاید قبلی</span>
                                            </button>
                                            <button class="t1-hero__nav-btn" type="button" data-bs-target="#carouselFirstPage" data-bs-slide="next">
                                                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                                                <span class="visually-hidden">اسلاید بعدی</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        <button type="button" class="t1-hero__scroll-cue" data-hero-scroll-cue aria-label="رفتن به بخش بعدی">
            <span></span>
        </button>
    </div>
</section>
@if(isset($settings['video_link']) && $settings['video_link'])
    <div class="modal fade modal-video" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent shadow-none border-0">
                <div class="modal-header border-0">
                    <h2 id="videoModalLabel" class="visually-hidden">ویدیو معرفی</h2>
                    <button type="button" id="closeBtn" class="shadow-none bg-transparent border-0"
                            data-bs-dismiss="modal" aria-label="بستن">
                        <i class="bi bi-x-lg d-flex light fs-3" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body" id="modalFrame">
                    {!! $settings['video_link'] !!}
                </div>
            </div>
        </div>
    </div>
@endif
@push('scripts')
<script>
$(function(){
    $('#closeBtn').click(function(){
        $('iframe').attr('src', $('iframe').attr('src'));
    });

    var heroCarousel = document.getElementById('carouselFirstPage');
    var heroCounter = document.querySelector('[data-hero-counter-current]');
    var heroTicks = document.querySelectorAll('[data-hero-tick]');
    if (heroCarousel && heroCounter) {
        heroCarousel.addEventListener('slid.bs.carousel', function (event) {
            heroCounter.textContent = String(event.to + 1).padStart(2, '0');
            heroTicks.forEach(function (tick, index) {
                tick.classList.toggle('is-done', index < event.to);
                tick.classList.toggle('is-active', index === event.to);
                if (index === event.to) {
                    var bar = tick.querySelector('[data-hero-progress]');
                    if (bar) {
                        bar.style.animation = 'none';
                        void bar.offsetWidth;
                        bar.style.animation = '';
                    }
                }
            });
        });
    }

    var heroSection = document.querySelector('.hero');
    var scrollCue = document.querySelector('[data-hero-scroll-cue]');
    if (heroSection && scrollCue) {
        scrollCue.addEventListener('click', function () {
            var next = heroSection.nextElementSibling;
            if (next) {
                next.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
});
</script>
@endpush

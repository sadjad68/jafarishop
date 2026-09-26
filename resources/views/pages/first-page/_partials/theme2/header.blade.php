@if(isset($banners) && count($banners))
<header class="home-slider">
    <div class="home-slider__stage">
        <div
            id="carouselExampleIndicators"
            class="carousel slide carousel-fade home-slider__carousel"
            data-bs-ride="carousel"
            data-bs-touch="true"
            data-reveal="fade"
        >
            <div class="carousel-inner">
                @foreach($banners as $banner)
                    <div class="carousel-item @if($loop->first) active @endif" data-bs-interval="6000">
                        @if(!empty($banner['link']))
                            <a href="{{ $banner['link'] }}" class="home-slider__link">
                                <img
                                    src="{{ $banner['image_mobile'] }}"
                                    class="d-lg-none d-block home-slider__img"
                                    alt="{{ $banner['title'] }}"
                                    title="{{ $banner['title'] }}"
                                    width="700"
                                    height="450"
                                    @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                >
                                <img
                                    src="{{ $banner['image'] }}"
                                    class="d-lg-block d-none home-slider__img"
                                    alt="{{ $banner['title'] }}"
                                    title="{{ $banner['title'] }}"
                                    width="1900"
                                    height="415"
                                    @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                >
                            </a>
                        @else
                            <div class="home-slider__link">
                                <img
                                    src="{{ $banner['image_mobile'] }}"
                                    class="d-lg-none d-block home-slider__img"
                                    alt="{{ $banner['title'] }}"
                                    title="{{ $banner['title'] }}"
                                    width="700"
                                    height="450"
                                    @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                >
                                <img
                                    src="{{ $banner['image'] }}"
                                    class="d-lg-block d-none home-slider__img"
                                    alt="{{ $banner['title'] }}"
                                    title="{{ $banner['title'] }}"
                                    width="1900"
                                    height="415"
                                    @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                >
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if(count($banners) > 1)
                <div class="home-slider__chrome">
                    <div class="home-slider__nav" role="group" aria-label="کنترل اسلایدر">
                        <button
                            type="button"
                            class="home-slider__btn"
                            data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev"
                            aria-label="اسلاید قبلی"
                        >
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        </button>
                        <button
                            type="button"
                            class="home-slider__btn"
                            data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next"
                            aria-label="اسلاید بعدی"
                        >
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="carousel-indicators home-slider__dots">
                        @foreach($banners as $key => $banner)
                            <button
                                type="button"
                                data-bs-target="#carouselExampleIndicators"
                                data-bs-slide-to="{{ $key }}"
                                class="@if($loop->first) active @endif"
                                @if($loop->first) aria-current="true" @endif
                                aria-label="اسلاید {{ $key + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>
@endif

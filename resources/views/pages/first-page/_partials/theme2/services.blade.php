@if (count($services) > 0)
<section class="services" data-services-swiper aria-labelledby="home-services-title">
    <div class="container">
        <div class="services-head" data-reveal>
            <div class="services-head__copy">
                <span class="services-head__eyebrow">پشتیبانی فروشگاه</span>
                <h2 id="home-services-title" class="services-head__title">
                    {{ trim(strip_tags(@$settings['first_page_service_title'] ?: 'خدمات ما')) }}
                </h2>
            </div>
            <div class="services-head__actions">
                <div class="services-nav" role="group" aria-label="ورق زدن خدمات">
                    <button type="button" class="services-nav__btn services-nav__btn--prev" aria-label="قبلی">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="services-nav__btn services-nav__btn--next" aria-label="بعدی">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </button>
                </div>
                <a href="{{ route('service.list') }}" class="services-head__all">
                    همه خدمات
                </a>
            </div>
        </div>
        <div class="swiper swiper-services" data-reveal>
            <div class="swiper-wrapper">
                @foreach ($services as $service)
                    @php
                        $service_excerpt = trim(strip_tags($service['short_description'] ?: $service['description'] ?: ''));
                    @endphp
                    <div class="swiper-slide">
                        <a href="{{ route('service.detail', ['url' => $service['url']]) }}" class="service-card">
                            <span class="service-card__media">
                                <img src="{{ $service['image'] }}"
                                     alt=""
                                     width="640"
                                     height="480"
                                     loading="lazy"
                                     aria-hidden="true">
                            </span>
                            <span class="service-card__body">
                                <span class="service-card__title">{{ $service['title'] }}</span>
                                @if ($service_excerpt !== '')
                                    <span class="service-card__excerpt">{{ \Illuminate\Support\Str::limit($service_excerpt, 96) }}</span>
                                @endif
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

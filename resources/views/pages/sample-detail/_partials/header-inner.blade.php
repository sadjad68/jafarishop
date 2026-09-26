@php
    $imageCount = isset($sample->imagesCollection) ? count($sample->imagesCollection) : (isset($sample['images']) ? count($sample['images']) : 0);
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'sample-detail-title',
    'bannerEyebrow' => 'نمونه کار',
    'bannerTitle' => @$sample->getH1PagesAttribute($sample),
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'نمونه کارها', 'url' => route('portfolio.list')],
        ['label' => $sample['title']],
    ],
    'bannerStatLogo' => $sample->getImage(),
    'bannerStatLogoAlt' => $sample['title'],
    'bannerStatNum' => max($imageCount, 1),
    'bannerStatLabel' => 'تصویر',
])

<div class="container">
    <div class="sk-service-intro">
        <div class="sk-service-intro__copy">
            @if(!empty($sample['short_description']))
                <div class="sk-service-intro__lead">
                    {{ $sample['short_description'] }}
                </div>
            @endif
            <div class="sk-cta-row">
                <a href="tel:{{ @$settings['main_phone_number'] }}"
                   id="Header-Call"
                   class="sk-cta">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    تماس با کارشناسان
                </a>
            </div>
        </div>
        <div class="sk-service-intro__media sk-sample-gallery{{ (int) $sample['double_image'] === 1 ? ' sk-sample-gallery--compare' : '' }}">
            <div class="sk-sample-gallery__stage">
                @if($sample['double_image'] == 1)
                    <span class="sk-sample-gallery__chip" aria-hidden="true">قبل / بعد</span>
                    <div class="swiper mySwiper-sample">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <section class="image-comparison w-100"
                                         data-component="image-comparison-slider">
                                    <div class="image-comparison__slider-wrapper">
                                        <label for="image-comparison-range" class="image-comparison__label"></label>
                                        <input type="range" min="0" max="100" value="50" class="image-comparison__range"
                                               id="image-compare-range" data-image-comparison-range="">

                                        <div class="image-comparison__image-wrapper image-comparison__image-wrapper--overlay"
                                             data-image-comparison-overlay="">
                                            <figure class="image-comparison__figure image-comparison__figure--overlay">
                                                <picture class="image-comparison__picture">
                                                    <source media="(max-width: 40em)"
                                                            srcset="{{ @$sample->getBeforeImage() }}">
                                                    <source media="(min-width: 40.0625em) and (max-width: 48em)"
                                                            srcset="{{ @$sample->getBeforeImage() }}">
                                                    <img src="{{ @$sample->getBeforeImage() }}"
                                                         alt="{{ $sample['title'] }} — قبل"
                                                         class="image-comparison__image">
                                                </picture>
                                                <figcaption class="image-comparison__caption image-comparison__caption--before">
                                                    <span class="image-comparison__caption-body">قبل</span>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <div class="image-comparison__slider" data-image-comparison-slider="">
                                            <span class="image-comparison__thumb" data-image-comparison-thumb="">
                                                <svg class="image-comparison__thumb-icon"
                                                     xmlns="http://www.w3.org/2000/svg" width="18" height="10"
                                                     viewBox="0 0 18 10" fill="currentColor" aria-hidden="true">
                                                    <path class="image-comparison__thumb-icon--left"
                                                          d="M12.121 4.703V.488c0-.302.384-.454.609-.24l4.42 4.214a.33.33 0 0 1 0 .481l-4.42 4.214c-.225.215-.609.063-.609-.24V4.703z"></path>
                                                    <path class="image-comparison__thumb-icon--right"
                                                          d="M5.879 4.703V.488c0-.302-.384-.454-.609-.24L.85 4.462a.33.33 0 0 0 0 .481l4.42 4.214c.225.215.609.063.609-.24V4.703z"></path>
                                                </svg>
                                            </span>
                                        </div>

                                        <div class="image-comparison__image-wrapper">
                                            <figure class="image-comparison__figure">
                                                <picture class="image-comparison__picture">
                                                    <source media="(max-width: 40em)"
                                                            srcset="{{ @$sample->getImage() }}">
                                                    <source media="(min-width: 40.0625em) and (max-width: 48em)"
                                                            srcset="{{ @$sample->getImage() }}">
                                                    <img src="{{ @$sample->getImage() }}"
                                                         alt="{{ $sample['title'] }} — بعد"
                                                         class="image-comparison__image">
                                                </picture>
                                                <figcaption class="image-comparison__caption image-comparison__caption--after">
                                                    <span class="image-comparison__caption-body">بعد</span>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="swiper-button-next" aria-label="بعدی"></div>
                        <div class="swiper-button-prev" aria-label="قبلی"></div>
                    </div>
                    <div thumbsSlider="" class="swiper mySwiper-thumb-sample sk-sample-gallery__thumbs">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ @$sample->getImage() }}"
                                     alt="{{ $sample['title'] }}"
                                     title="{{ $sample['title'] }}">
                            </div>
                        </div>
                    </div>
                @elseif($sample['double_image'] == 0 && count($sample['images']) == 1)
                    <div class="swiper mySwiper-sample">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ @$sample->getImage() }}"
                                     alt="{{ $sample['title'] }}"
                                     title="{{ $sample['title'] }}">
                            </div>
                        </div>
                        <div class="swiper-button-next" aria-label="بعدی"></div>
                        <div class="swiper-button-prev" aria-label="قبلی"></div>
                    </div>
                    <div thumbsSlider="" class="swiper mySwiper-thumb-sample sk-sample-gallery__thumbs">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ @$sample->getImage() }}"
                                     alt="{{ $sample['title'] }}"
                                     title="{{ $sample['title'] }}">
                            </div>
                        </div>
                    </div>
                @elseif($sample['double_image'] == 0 && count($sample['images']) > 1)
                    <span class="sk-sample-gallery__chip" aria-hidden="true">
                        <i class="bi bi-images"></i>
                        {{ count($sample->imagesCollection) }} تصویر
                    </span>
                    <div class="swiper mySwiper-sample">
                        <div class="swiper-wrapper">
                            @foreach($sample->imagesCollection as $sample_image)
                                <div class="swiper-slide">
                                    <img src="{{ @$sample_image->getImage() }}"
                                         alt="{{ $sample['title'] }}"
                                         title="{{ $sample['title'] }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next" aria-label="بعدی"></div>
                        <div class="swiper-button-prev" aria-label="قبلی"></div>
                    </div>
                    <div thumbsSlider="" class="swiper mySwiper-thumb-sample sk-sample-gallery__thumbs">
                        <div class="swiper-wrapper">
                            @foreach($sample->imagesCollection as $sample_image)
                                <div class="swiper-slide">
                                    <img src="{{ @$sample_image->getImage() }}"
                                         alt="{{ $sample['title'] }}"
                                         title="{{ $sample['title'] }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <img src="{{ @$sample->getImage() }}"
                         alt="{{ $sample['title'] }}"
                         title="{{ $sample['title'] }}"
                         width="640"
                         height="420">
                @endif
            </div>
        </div>
    </div>
</div>

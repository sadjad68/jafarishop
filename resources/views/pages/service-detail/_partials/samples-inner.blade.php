@if(count($samples) > 0)
    <section class="sk-samples-block" aria-labelledby="service-samples-title">
        <div id="samples"></div>
        <div class="container">
            <div class="sk-section-head">
                <span class="sk-section-head__eyebrow">گالری</span>
                <h2 id="service-samples-title" class="sk-section-head__title">نمونه کارهای {{ $service['title'] }}</h2>
            </div>
            <div class="sk-sample-grid">
                @foreach($samples as $sample)
                    @php
                        $hasUrl = !empty($sample['url']);
                        $imageCount = isset($sample['images']) ? count($sample['images']) : 0;
                        $isCompare = (int) $sample['double_image'] === 1;
                        $isGallery = !$isCompare && $imageCount > 1;
                        $detailUrl = $hasUrl ? route('portfolio.detail', ['url' => $sample['url']]) : null;
                    @endphp

                    <article class="sk-sample-card sk-sample-card--showcase{{ $isCompare ? ' sk-sample-card--compare' : '' }}">
                        <div class="sk-sample-card__media">
                            @if($isCompare)
                                <span class="sk-sample-card__badge" aria-hidden="true">قبل / بعد</span>
                                <section class="image-comparison" data-component="image-comparison-slider">
                                    <div class="image-comparison__slider-wrapper">
                                        <label for="image-compare-range-{{ $sample['id'] }}" class="image-comparison__label"></label>
                                        <input type="range" min="0" max="100" value="50" class="image-comparison__range"
                                               id="image-compare-range-{{ $sample['id'] }}" data-image-comparison-range="">

                                        <div class="image-comparison__image-wrapper image-comparison__image-wrapper--overlay"
                                             data-image-comparison-overlay="">
                                            <figure class="image-comparison__figure image-comparison__figure--overlay">
                                                <picture class="image-comparison__picture">
                                                    <source media="(max-width: 40em)"
                                                            srcset="{{ $sample->getBeforeImage() }}">
                                                    <source media="(min-width: 40.0625em) and (max-width: 48em)"
                                                            srcset="{{ $sample->getBeforeImage() }}">
                                                    <img src="{{ $sample->getBeforeImage() }}"
                                                         alt="{{ $sample['title'] }} — قبل"
                                                         title="{{ $sample['title'] }}"
                                                         class="image-comparison__image"
                                                         loading="lazy">
                                                </picture>
                                                <figcaption class="image-comparison__caption image-comparison__caption--before">
                                                    <span class="image-comparison__caption-body">قبل</span>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <div class="image-comparison__slider" data-image-comparison-slider="">
                                            <span class="image-comparison__thumb" data-image-comparison-thumb="">
                                                <svg class="image-comparison__thumb-icon" xmlns="http://www.w3.org/2000/svg"
                                                     width="18" height="10" viewBox="0 0 18 10" fill="currentColor"
                                                     aria-hidden="true">
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
                                                    <source media="(max-width: 40em)" srcset="{{ $sample->getImage() }}">
                                                    <source media="(min-width: 40.0625em) and (max-width: 48em)"
                                                            srcset="{{ $sample->getImage() }}">
                                                    <img src="{{ $sample->getImage() }}"
                                                         alt="{{ $sample['title'] }} — بعد"
                                                         title="{{ $sample['title'] }}"
                                                         class="image-comparison__image"
                                                         loading="lazy">
                                                </picture>
                                                <figcaption class="image-comparison__caption image-comparison__caption--after">
                                                    <span class="image-comparison__caption-body">بعد</span>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </section>
                            @elseif($imageCount === 1 || $imageCount === 0)
                                @if($detailUrl)
                                    <a href="{{ $detailUrl }}" class="sk-sample-card__hit" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $sample->getImage() }}"
                                             alt="{{ $sample['title'] }}"
                                             title="{{ $sample['title'] }}"
                                             loading="lazy"
                                             width="480"
                                             height="360">
                                    </a>
                                @else
                                    <img src="{{ $sample->getImage() }}"
                                         alt="{{ $sample['title'] }}"
                                         title="{{ $sample['title'] }}"
                                         loading="lazy"
                                         width="480"
                                         height="360">
                                @endif
                            @else
                                <span class="sk-sample-card__badge" aria-hidden="true">
                                    <i class="bi bi-images"></i>
                                    {{ $imageCount }} تصویر
                                </span>
                                @if($detailUrl)
                                    <a href="{{ $detailUrl }}" class="sk-sample-card__hit" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $sample->getImage() }}"
                                             alt="{{ $sample['title'] }}"
                                             title="{{ $sample['title'] }}"
                                             loading="lazy"
                                             width="480"
                                             height="360">
                                    </a>
                                @else
                                    <button type="button"
                                            class="sk-sample-card__hit sk-sample-card__hit--button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#exampleModal{{ $sample['id'] }}"
                                            aria-label="مشاهده گالری {{ $sample['title'] }}">
                                        <img src="{{ $sample->getImage() }}"
                                             alt="{{ $sample['title'] }}"
                                             title="{{ $sample['title'] }}"
                                             loading="lazy"
                                             width="480"
                                             height="360">
                                    </button>
                                @endif
                            @endif
                            <span class="sk-sample-card__shine" aria-hidden="true"></span>
                        </div>

                        <div class="sk-sample-card__name">
                            <span class="sk-sample-card__title">{{ $sample['title'] }}</span>
                            @if($detailUrl)
                                <a href="{{ $detailUrl }}" class="sk-sample-card__action">
                                    مشاهده
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </a>
                            @elseif($isGallery)
                                <button type="button"
                                        class="sk-sample-card__action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal{{ $sample['id'] }}">
                                    مشاهده
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>

                        @if($isGallery && !$hasUrl)
                            <div class="modal fade sk-sample-modal" id="exampleModal{{ $sample['id'] }}" tabindex="-1"
                                 aria-labelledby="sampleModalLabel{{ $sample['id'] }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content sk-sample-modal__content">
                                        <div class="modal-header border-0 p-0">
                                            <h2 id="sampleModalLabel{{ $sample['id'] }}" class="visually-hidden">
                                                {{ $sample['title'] }}
                                            </h2>
                                            <button type="button"
                                                    class="btn sk-sample-modal__close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="بستن">
                                                <i class="bi bi-x-lg" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body p-0">
                                            <div class="swiper mySwiper-sample p-1">
                                                <div class="swiper-wrapper">
                                                    @foreach($sample['images'] as $sample_image)
                                                        <div class="swiper-slide">
                                                            <img src="{{ $sample_image->getImage('big') }}"
                                                                 alt="{{ $sample['title'] }}"
                                                                 title="{{ $sample['title'] }}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="swiper-button-next"></div>
                                                <div class="swiper-button-prev"></div>
                                            </div>
                                            <div thumbsSlider="" class="swiper mySwiper-thumb-sample p-1">
                                                <div class="swiper-wrapper py-2">
                                                    @foreach($sample['images'] as $sample_image)
                                                        <div class="swiper-slide">
                                                            <img src="{{ $sample_image->getImage('small') }}"
                                                                 alt="{{ $sample['title'] }}"
                                                                 title="{{ $sample['title'] }}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif

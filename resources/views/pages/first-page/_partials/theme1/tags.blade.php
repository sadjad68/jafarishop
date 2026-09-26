@if(count($tags) > 0)
    @foreach($tags as $tag)
        @if(count($tag['products']) > 0)
            <section class="offer tags t1-section t1-section--brand t1-section--tight" data-offer-swiper>
                <div class="container">
                    <div class="offer-strip" data-reveal-group>
                        @include('pages.first-page._partials.theme1._offer-badge', [
                            't1_badge_title' => @$tag['title'],
                            't1_badge_link' => route('tag.detail', ['url' => @$tag['url']]),
                            't1_badge_icon' => @$tag->item_first_page_icon,
                        ])
                        <div class="offer-strip__body" data-reveal>
                            <div class="swiper swiper-offer">
                                <div class="swiper-wrapper">
                                    @foreach($tag['products'] as $tag_product)
                                        <div class="swiper-slide bg-transparent">
                                            <div class="offer-card">
                                                <a href="{{ \App\Library\SiteUrl::product($tag_product) }}">
                                                    <div class="offer-card__media">
                                                        @if(@$tag_product->percent)
                                                            <span class="offer-card__percent">{{$tag_product->percent}}%</span>
                                                        @endif
                                                        <img src="{{$tag_product->getImage('medium')}}" class="w-100" alt="{{$tag_product['title']}}" title="{{$tag_product['title']}}" loading="lazy">
                                                    </div>
                                                    <p class="offer-card__title">{{$tag_product['title']}}</p>
                                                    @if ($tag_product['final_price'] != 0 || $tag_product['price'])
                                                        <div class="offer-card__price">
                                                            @if($tag_product['final_price'] != 0)
                                                                <span class="offer-card__price-now font-num-r">{{number_format($tag_product['final_price'])}} تومان</span>
                                                            @endif
                                                            @if($tag_product['discounted_price'])
                                                                <del class="offer-card__price-old font-num-r">{{number_format($tag_product['price'])}} تومان</del>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="offer-card__price">
                                                            <span class="offer-card__price-now font-num-r">تماس بگیرید</span>
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="offer-nav">
                                <button type="button" class="offer-nav__btn offer-nav__btn--prev" aria-label="آیتم قبلی">
                                    <i class="bi bi-chevron-right d-flex" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="offer-nav__btn offer-nav__btn--next" aria-label="آیتم بعدی">
                                    <i class="bi bi-chevron-left d-flex" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
@endif

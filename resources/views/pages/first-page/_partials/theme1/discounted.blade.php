@if(count($timer_products) > 0)
    <section class="offer t1-section t1-section--brand t1-section--tight" data-offer-swiper>
        <div class="container">
            <div class="offer-strip" data-reveal-group>
                @include('pages.first-page._partials.theme1._offer-badge', [
                    't1_badge_title' => 'پیشنهاد شگفت‌انگیز',
                    't1_badge_link' => route('product.get-discounted-list'),
                ])
                <div class="offer-strip__body" data-reveal>
                    <div class="swiper swiper-offer">
                        <div class="swiper-wrapper">
                            @foreach($timer_products as $timer)
                                <div class="swiper-slide bg-transparent">
                                    <div class="offer-card w-100">
                                        <a href="{{ \App\Library\SiteUrl::product($timer) }}">
                                            <div class="offer-card__media">
                                                @if($timer->percent)
                                                    <span class="offer-card__percent">{{$timer->percent}}%</span>
                                                @endif
                                                <img src="{{$timer->getImage('medium')}}" class="w-100"
                                                     alt="{{$timer['title']}}" title="{{$timer['title']}}"
                                                     loading="lazy">
                                            </div>
                                            <p class="offer-card__title">{{$timer['title']}}</p>
                                            @if ($timer['final_price'] != 0 || $timer['price'])
                                                <div class="offer-card__price">
                                                    @if($timer['final_price'] != 0)
                                                        <span class="offer-card__price-now font-num-r">{{number_format($timer['final_price'])}} تومان</span>
                                                    @endif
                                                    @if($timer['price'])
                                                        <del class="offer-card__price-old font-num-r">{{number_format($timer['price'])}} تومان</del>
                                                    @endif
                                                </div>
                                            @endif
                                            <ul class="offer-card__countdown" dir="ltr" aria-label="زمان باقی‌مانده">
                                                <li><span id="days{{$timer['id']}}"></span>روز</li>
                                                <li><span id="hours{{$timer['id']}}"></span>ساعت</li>
                                                <li><span id="minutes{{$timer['id']}}"></span>دقیقه</li>
                                                <li><span id="seconds{{$timer['id']}}"></span>ثانیه</li>
                                            </ul>
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
    @include('pages.first-page._partials.timer-script')
@endif

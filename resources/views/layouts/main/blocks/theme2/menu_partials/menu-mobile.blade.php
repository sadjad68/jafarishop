<div class="item-menu-mobile d-lg-none d-block menu-mobile site-header__mobile">
    <div class="site-header__mobile-bar">
        <div class="site-header__mobile-side site-header__mobile-side--menu">
            <button class="site-header__mobile-btn btn-list btn-text border-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" aria-label="منو">
                <i class="bi bi-list d-flex fs-4"></i>
            </button>
        </div>
        <a href="{{ route('index') }}" class="site-header__logo site-header__logo--center">
            <img class="logo-menu-mobile" src="{{ $settings['logo'] }}" alt="{{ $settings['siteName_fa'] }}"
                title="{{ $settings['siteName_fa'] }}" width="40" height="36" />
        </a>
        <div class="site-header__mobile-side site-header__mobile-side--action">
                @if(@$settings['ads_show'] == 1)
                    <div class="d-flex align-items-center justify-content-around">
                        <div class="p-1 align-self-center" id="menu">
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="{{ route('basket.cart') }}"
                                   class="site-header__mobile-btn text-center d-flex flex-column align-items-center small font-re"
                                   aria-label="سبد خرید"
                                   :aria-label="basketItemCount > 0 ? ('سبد خرید، ' + basketItemCount + ' کالا') : 'سبد خرید'">
                                    <span class="site-cart-icon">
                                        <i class="bi bi-bag fs-4 mb-1 d-flex" aria-hidden="true"></i>
                                        <span class="cart-num"
                                              v-if="basketItemCount > 0"
                                              v-cloak
                                              aria-hidden="true">@{{ basketItemCount > 99 ? '99+' : basketItemCount }}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="p-1 align-self-center">
                            <a href="{{ route('panel.dashboard') }}"
                               class="site-header__mobile-btn text-center d-flex flex-column align-items-center small font-re"
                               aria-label="{{ auth()->check() ? (auth()->user()->full_name ?: 'پنل کاربری') : 'ورود' }}">
                                <i class="bi bi-person fs-4 mb-1 d-flex"></i>
                            </a>
                        </div>
                    </div>
                @elseif (isset($settings['main_phone_number']))
                    <a href="tel:{{ $settings['main_phone_number'] }}"
                        class="site-header__mobile-btn site-header__mobile-btn--phone d-flex align-items-center font-bold"
                        aria-label="تماس با {{ $settings['main_phone_number'] }}">
                        <i class="bi bi-telephone-fill d-flex" aria-hidden="true"></i>
                        <span dir="ltr">@toPersianNumber($settings['main_phone_number'])</span>
                    </a>
                @else
                    <a href="{{ route('us.contact') }}"
                        class="site-header__mobile-btn site-header__mobile-btn--phone d-flex align-items-center font-bold"
                        aria-label="تماس">
                        <i class="bi bi-telephone-fill d-flex" aria-hidden="true"></i>
                    </a>
                @endif
        </div>
    </div>
    @mobile
        <div class="col-12 p-1 site-header__mobile-search">
            <div class="search position-relative">
                @include('layouts.main.blocks.' . $theme_provider->getValue() . '.search')
            </div>
        </div>
    @endmobile
</div>

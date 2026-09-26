<div class="t1-mobile d-lg-none d-block">
    <div class="t1-mobile-bar">
        <div class="t1-mobile-bar__menu">
            <button class="t1-mobile-btn" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                    aria-controls="offcanvasExample" aria-label="باز کردن منو">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </div>
        <a href="{{ route('index') }}" class="t1-mobile-bar__brand">
            @yield('logo')
        </a>
        <div class="t1-mobile-bar__end">
            @if (@$settings['ads_show'] == 1)
                <div class="t1-mobile-bar__actions" id="menu">
                    <a href="{{ route('basket.cart') }}"
                       class="t1-mobile-btn"
                       aria-label="سبد خرید"
                       :aria-label="basketItemCount > 0 ? ('سبد خرید، ' + basketItemCount + ' کالا') : 'سبد خرید'">
                        <span class="t1-mobile-cart">
                            <i class="bi bi-bag" aria-hidden="true"></i>
                            <span class="cart-num"
                                  v-if="basketItemCount > 0"
                                  v-cloak
                                  aria-hidden="true">@{{ basketItemCount > 99 ? '99+' : basketItemCount }}</span>
                        </span>
                    </a>
                    <a href="{{ route('panel.dashboard') }}"
                       class="t1-mobile-btn"
                       aria-label="{{ auth()->check() ? (auth()->user()->full_name ?: 'پنل کاربری') : 'ورود' }}">
                        <i class="bi bi-person" aria-hidden="true"></i>
                    </a>
                </div>
            @elseif (isset($settings['main_phone_number']))
                <a href="tel:{{ $settings['main_phone_number'] }}"
                   class="t1-mobile-btn t1-mobile-btn--phone"
                   aria-label="تماس با {{ $settings['main_phone_number'] }}">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    <span dir="ltr">@toPersianNumber($settings['main_phone_number'])</span>
                </a>
            @else
                <a href="{{ route('us.contact') }}"
                   class="t1-mobile-btn t1-mobile-btn--phone"
                   aria-label="تماس">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                </a>
            @endif
        </div>
    </div>
    @if ($theme_provider->hasSection('siteSections', 'search'))
        <div class="t1-mobile-search">
            @include('layouts.main.blocks.theme1.menu_partials.mobile-search')
        </div>
    @endif
</div>

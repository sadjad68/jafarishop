@if (!request()->routeIs('product.detail', 'product.legacy'))
    <nav class="menu-app t1-menu-bar d-flex d-lg-none" aria-label="منوی موبایل"@if ($settings['disable_shop'] == 1) id="menu"@endif>
        <a href="{{ route('index') }}"
           class="t1-menu-bar__link {{ request()->routeIs('index') ? 'is-active' : '' }}">
            <i class="bi bi-house-door" aria-hidden="true"></i>
            <span>خانه</span>
        </a>
        @if ($settings['disable_shop'] == 0)
            @if (!empty($hasNestedProductCats))
                <button type="button"
                        class="t1-menu-bar__link"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasCat"
                        aria-controls="offcanvasCat"
                        aria-label="دسته‌بندی‌ها">
                    <i class="bi bi-grid" aria-hidden="true"></i>
                    <span>دسته‌بندی</span>
                </button>
            @else
                <a href="{{ route('category.list') }}"
                   class="t1-menu-bar__link {{ request()->routeIs('category.*') ? 'is-active' : '' }}">
                    <i class="bi bi-grid" aria-hidden="true"></i>
                    <span>دسته‌بندی</span>
                </a>
            @endif
            <div class="t1-menu-bar__item" id="menu">
                <a href="{{ route('basket.cart') }}"
                   class="t1-menu-bar__link {{ request()->routeIs('basket.*') ? 'is-active' : '' }}"
                   aria-label="سبد خرید"
                   :aria-label="basketItemCount > 0 ? ('سبد خرید، ' + basketItemCount + ' کالا') : 'سبد خرید'">
                    <span class="t1-mobile-cart">
                        <i class="bi bi-bag" aria-hidden="true"></i>
                        <span class="cart-num"
                              v-if="basketItemCount > 0"
                              v-cloak
                              aria-hidden="true">@{{ basketItemCount > 99 ? '99+' : basketItemCount }}</span>
                    </span>
                    <span>سبد خرید</span>
                </a>
            </div>
        @else
            <a href="{{ route('us.contact') }}"
               class="t1-menu-bar__link {{ request()->routeIs('us.contact') ? 'is-active' : '' }}">
                <i class="bi bi-telephone" aria-hidden="true"></i>
                <span>تماس</span>
            </a>
        @endif
        <a href="{{ route('panel.dashboard') }}"
           class="t1-menu-bar__link {{ request()->routeIs('panel.*') || request()->routeIs('auth.*') ? 'is-active' : '' }}">
            <i class="bi bi-person" aria-hidden="true"></i>
            <span>@auth پروفایل @else ورود @endauth</span>
        </a>
    </nav>
@endif

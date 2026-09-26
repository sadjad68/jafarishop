     <!-- menu app -->
@if (!request()->routeIs('product.detail'))
    <nav class="menu-app site-menu-bar d-flex d-lg-none" aria-label="منوی موبایل">
        <a href="{{ route('index') }}"
           class="site-menu-bar__link d-flex flex-column align-items-center {{ request()->routeIs('index') ? 'is-active' : '' }}">
            <i class="bi bi-house-door" aria-hidden="true"></i>
            <span>خانه</span>
        </a>
        @if (!empty($hasNestedProductCats))
            <button type="button"
               class="site-menu-bar__link d-flex flex-column align-items-center"
               data-bs-toggle="offcanvas"
               data-bs-target="#offcanvasCat"
               aria-controls="offcanvasCat"
               aria-label="دسته‌بندی‌ها">
                <i class="bi bi-grid" aria-hidden="true"></i>
                <span>دسته‌بندی</span>
            </button>
        @else
            <a href="{{ route('category.list') }}"
               class="site-menu-bar__link d-flex flex-column align-items-center {{ request()->routeIs('category.*') ? 'is-active' : '' }}">
                <i class="bi bi-grid" aria-hidden="true"></i>
                <span>دسته‌بندی</span>
            </a>
        @endif
        <div class="site-menu-bar__item" id="menu">
            <a href="{{ route('basket.cart') }}"
               class="site-menu-bar__link d-flex flex-column align-items-center {{ request()->routeIs('basket.*') ? 'is-active' : '' }}"
               aria-label="سبد خرید"
               :aria-label="basketItemCount > 0 ? ('سبد خرید، ' + basketItemCount + ' کالا') : 'سبد خرید'">
                <span class="site-cart-icon">
                    <i class="bi bi-bag" aria-hidden="true"></i>
                    <span class="cart-num"
                          v-if="basketItemCount > 0"
                          v-cloak
                          aria-hidden="true">@{{ basketItemCount > 99 ? '99+' : basketItemCount }}</span>
                </span>
                <span>سبد خرید</span>
            </a>
        </div>
        <a href="{{ route('panel.dashboard') }}"
           class="site-menu-bar__link d-flex flex-column align-items-center {{ request()->routeIs('panel.*') || request()->routeIs('auth.*') ? 'is-active' : '' }}">
            <i class="bi bi-person" aria-hidden="true"></i>
            <span>@auth پروفایل @else ورود @endauth</span>
        </a>
    </nav>
@endif

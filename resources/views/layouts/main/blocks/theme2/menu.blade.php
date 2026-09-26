<div class="site-header-wrap">
    @include('layouts.main.blocks.' . $theme_provider->getValue() . '.banner')
    <nav class="menu site-header m-0 p-0" aria-label="ناوبری اصلی">
    <div class="site-header__shell container-fluid px-xl-5 px-lg-3 px-2">
        @mobile
            @include('layouts.main.blocks.' . $theme_provider->getValue() . '.menu_partials.menu-mobile')
        @else
            <div class="item-menu-desktop d-lg-block d-none">
                <div class="site-header__primary row align-items-center g-2 py-2">
                    <div class="col-xl-3 col-lg-4 site-header__brand">
                        <a href="{{ route('index') }}" class="site-header__logo link-logo d-inline-flex">
                            <img src="{{ $settings['logo'] }}" alt="{{ $settings['siteName_fa'] }}"
                                title="{{ $settings['siteName_fa'] }}" width="100" height="60"
                                class="h-auto logo-menu-desktop" />
                        </a>
                    </div>
                    <div class="col-xl-5 col-lg-5 site-header__search">
                        <div class="search position-relative">
                            @include('layouts.main.blocks.' . $theme_provider->getValue() . '.search')
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-3 site-header__actions" id="menu">
                        <ul class="site-header__actions-list p-0 m-0 d-flex align-items-center justify-content-end gap-2 flex-wrap">
                            @if (isset($settings['main_phone_number']))
                                <li class="list-unstyled site-header__action site-header__action--phone">
                                    <a @if (isset($settings['main_phone_number'])) href="tel:{{ $settings['main_phone_number'] }}" @else href="{{ route('us.contact') }}" @endif
                                        class="site-header__action-link site-header__action-link--cta d-flex align-items-center gap-2 font-bold">
                                        <i class="bi bi-telephone-fill d-flex"></i>
                                        <span dir="ltr">@toPersianNumber($settings['main_phone_number'])</span>
                                    </a>
                                </li>
                            @endif
                            <li class="list-unstyled site-header__action site-header__action--cart position-relative" v-cloak>
                                <a href="{{ route('basket.cart') }}" class="site-header__action-link" aria-label="سبد خرید">
                                    <i class="bi bi-bag d-flex"></i>
                                </a>
                                <span class="cart-num">@{{ basketItemCount }}</span>
                            </li>
                            <li class="list-unstyled site-header__action site-header__action--profile">
                                @php
                                    $headerUserLabel = auth()->check()
                                        ? (auth()->user()->full_name ?: 'پنل کاربری')
                                        : 'ورود';
                                @endphp
                                <a href="{{ route('panel.dashboard') }}"
                                    class="site-header__action-link d-flex align-items-center gap-2"
                                    title="{{ $headerUserLabel }}">
                                    <i class="bi bi-person d-flex"></i>
                                    <span class="site-header__action-label">
                                        {{ $headerUserLabel }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="site-header__nav-clip">
                <div class="site-header__nav">
                    @php
                        $menuPathActive = function ($url) {
                            $path = trim(parse_url($url, PHP_URL_PATH) ?? $url, '/');
                            if ($path === '') {
                                return request()->is('/');
                            }
                            return request()->is($path) || request()->is($path . '/*');
                        };
                        $megaPanels = [];
                    @endphp
                    <div class="site-header__nav-scroll">
                        <button type="button" class="site-header__nav-scroll-btn site-header__nav-scroll-btn--left" aria-label="اسکرول منو به چپ">
                            <i class="bi bi-chevron-left d-flex"></i>
                        </button>
                        <div class="site-header__nav-track">
                            <ul class="p-0 m-0 d-flex align-items-center gap-2 main site-header__nav-list site-nav">
                            @foreach ($settings['menu_links'] as $menu_item)
                                @if ($menu_item['type'] == 'default')
                                    @php $isActive = $menuPathActive(trim($menu_item['url'])); @endphp
                                    <li class="list-unstyled site-nav__item">
                                        <a href="{{ url(trim($menu_item['url'])) }}" class="d-flex align-items-center link-main site-nav__link {{ $isActive ? 'is-active' : '' }}">
                                            {{ $menu_item['title'] }}
                                        </a>
                                    </li>
                                @elseif($menu_item['type'] == 'product')
                                    @if (@$menu_item['sidebyside'] == 'no' && $menu_product_categories)
                                        @php
                                            $isActive = request()->routeIs('category.*');
                                            $megaPanelId = 'mega-product-all';
                                            $hasMegaKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_product_categories);
                                            if ($hasMegaKids) {
                                                $megaPanels[] = [
                                                    'id' => $megaPanelId,
                                                    'menu_items' => $menu_product_categories,
                                                    'route_name' => 'category.detail',
                                                ];
                                            }
                                        @endphp
                                        <li class="list-unstyled site-nav__item{{ $hasMegaKids ? ' mega site-nav__item--mega' : '' }}"
                                            @if ($hasMegaKids) data-mega-panel="{{ $megaPanelId }}" @endif>
                                            <a href="{{ route('category.list') }}" class="d-flex align-items-center link-main site-nav__link {{ $isActive ? 'is-active' : '' }}">
                                                {{ $menu_item['title'] }}
                                                @if ($hasMegaKids)
                                                    <i class="bi bi-chevron-down d-flex ms-1 site-nav__chevron"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @elseif(@$menu_item['sidebyside'] == 'yes' && $menu_product_categories)
                                        @foreach ($menu_product_categories as $menu_product_category)
                                            @php
                                                $catUrl = \App\Library\SiteUrl::category($menu_product_category, false);
                                                $isActive = $menuPathActive($catUrl);
                                                $megaPanelId = 'mega-product-' . $menu_product_category['id'];
                                                $hasMegaKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_product_category['childrenInMenu'] ?? null);
                                                if ($hasMegaKids) {
                                                    $megaPanels[] = [
                                                        'id' => $megaPanelId,
                                                        'menu_items' => $menu_product_category['childrenInMenu'],
                                                        'route_name' => 'category.detail',
                                                    ];
                                                }
                                            @endphp
                                            <li class="list-unstyled site-nav__item{{ $hasMegaKids ? ' mega site-nav__item--mega' : '' }}"
                                                @if ($hasMegaKids) data-mega-panel="{{ $megaPanelId }}" @endif>
                                                <a href="{{ \App\Library\SiteUrl::category($menu_product_category) }}"
                                                    class="d-flex align-items-center link-main site-nav__link {{ $isActive ? 'is-active' : '' }}">
                                                    {{ $menu_product_category['title'] }}
                                                    @if ($hasMegaKids)
                                                        <i class="bi bi-chevron-down d-flex ms-1 site-nav__chevron"></i>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                @elseif($menu_item['type'] == 'service')
                                    @if (@$menu_item['sidebyside'] == 'no' && $menu_services)
                                        @php
                                            $isActive = request()->routeIs('service.*');
                                            $megaPanelId = 'mega-service-all';
                                            $hasMegaKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_services);
                                            if ($hasMegaKids) {
                                                $megaPanels[] = [
                                                    'id' => $megaPanelId,
                                                    'menu_items' => $menu_services,
                                                    'route_name' => 'service.detail',
                                                ];
                                            }
                                        @endphp
                                        <li class="list-unstyled site-nav__item{{ $hasMegaKids ? ' mega site-nav__item--mega' : '' }}"
                                            @if ($hasMegaKids) data-mega-panel="{{ $megaPanelId }}" @endif>
                                            <a href="{{ route('service.list') }}" class="d-flex align-items-center link-main site-nav__link {{ $isActive ? 'is-active' : '' }}">
                                                {{ $menu_item['title'] }}
                                                @if ($hasMegaKids)
                                                    <i class="bi bi-chevron-down d-flex ms-1 site-nav__chevron"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @elseif(@$menu_item['sidebyside'] == 'yes' && $menu_services)
                                        @foreach ($menu_services as $menu_service)
                                            @php
                                                $serviceUrl = route('service.detail', ['url' => $menu_service['url']], false);
                                                $isActive = $menuPathActive($serviceUrl);
                                                $megaPanelId = 'mega-service-' . $menu_service['id'];
                                                $hasMegaKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_service['childrenInMenu'] ?? null);
                                                if ($hasMegaKids) {
                                                    $megaPanels[] = [
                                                        'id' => $megaPanelId,
                                                        'menu_items' => $menu_service['childrenInMenu'],
                                                        'route_name' => 'service.detail',
                                                    ];
                                                }
                                            @endphp
                                            <li class="list-unstyled site-nav__item{{ $hasMegaKids ? ' mega site-nav__item--mega' : '' }}"
                                                @if ($hasMegaKids) data-mega-panel="{{ $megaPanelId }}" @endif>
                                                <a href="{{ route('service.detail', ['url' => $menu_service['url']]) }}"
                                                    class="d-flex align-items-center link-main site-nav__link {{ $isActive ? 'is-active' : '' }}">
                                                    {{ $menu_service['title'] }}
                                                    @if ($hasMegaKids)
                                                        <i class="bi bi-chevron-down d-flex ms-1 site-nav__chevron"></i>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                        </div>
                        <button type="button" class="site-header__nav-scroll-btn site-header__nav-scroll-btn--right" aria-label="اسکرول منو به راست">
                            <i class="bi bi-chevron-right d-flex"></i>
                        </button>
                    </div>
                    @if (count($megaPanels))
                        <div class="site-header__mega-panels">
                            @foreach ($megaPanels as $megaPanel)
                                @include(
                                    'layouts.main.blocks.' . $theme_provider->getValue() . '.menu_partials.mega-menu-desktop',
                                    [
                                        'menu_items' => $megaPanel['menu_items'],
                                        'route_name' => $megaPanel['route_name'],
                                        'mega_panel_id' => $megaPanel['id'],
                                    ]
                                )
                            @endforeach
                        </div>
                    @endif
                </div>
                </div>
            </div>
        @endmobile
    </div>
    </nav>
</div>
@mobile
    @php
        $hasNestedProductCats = false;
        $firstNestedCatIndex = null;
        foreach ($menu_product_categories ?? [] as $nestedCatIndex => $nestedCat) {
            if (\App\Modules\Setting\Helper\MenuHelper::hasChildren($nestedCat['childrenInMenu'] ?? null)) {
                $hasNestedProductCats = true;
                if ($firstNestedCatIndex === null) {
                    $firstNestedCatIndex = $nestedCatIndex;
                }
            }
        }
    @endphp
    @if (@$settings['ads_show'] == 0)
        @include('layouts.main.blocks.' . $theme_provider->getValue() . '.menu-app')
    @endif
    @include('layouts.main.blocks.' . $theme_provider->getValue() . '.menu_partials.menu-sidebar')
@endmobile

<section class="menu">
        @mobile
        @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.mobile-menu")
        @else
            {{-- menu desktop --}}
            @php
                $t1CurrentPath = trim(request()->path(), '/');
            @endphp
            <div class="desktop-menu d-lg-block d-none">
                <div class="t1-header-top">
                    <div class="container">
                <div class="top">
                    <div class="t1-topbar">
                        @if($main_branch)
                            <a href="{{$main_branch['map']}}" rel="nofollow" class="t1-topbar__link">
                                <i class="bi bi-geo-alt t1-topbar__icon" aria-hidden="true"></i>
                                <span class="t1-topbar__label">{{$main_branch['title']}}</span>
                                <span class="t1-topbar__meta">{{'مسیریابی ' . @$settings['location_header_title']}}</span>
                            </a>
                        @endif
                        <ul class="t1-topbar__end p-0 m-0">
                            @if($settings['disable_shop'] == 0)
                                <li class="t1-topbar__item">
                                    <a href="{{route('panel.dashboard')}}" class="t1-topbar__link">
                                        <i class="bi bi-person t1-topbar__icon" aria-hidden="true"></i>
                                        <span class="t1-topbar__label">
                                            @auth
                                                {{Auth::user()->full_name}}
                                            @else
                                                ورود / ثبت‌نام
                                            @endauth
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if(isset($settings['main_phone_number']))
                                <li class="t1-topbar__item">
                                    <a href="tel:{{$settings['main_phone_number']}}"
                                       class="t1-topbar__phone"
                                       aria-label="تماس تلفنی">
                                        <i class="bi bi-telephone" aria-hidden="true"></i>
                                        <span dir="ltr" id="Menu-Call">@toPersianNumber($settings['main_phone_number'])</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                    </div>
                </div>
                <div class="container">
                <div class="main-menu t1-nav-bar">
                    <a href="{{route('index')}}" class="t1-brand">
                        @yield('logo')
                        <img src="{{$settings['logo']}}" id="lightLogo" width="120"
                             alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}"
                             class="logo-menu d-none">
                    </a>
                    <nav class="t1-nav" aria-label="منوی اصلی">
                        <ul class="menu-items p-0 m-0 d-flex align-items-center">
                            @foreach($settings['menu_links'] as $menu_item)
                                @if($menu_item['type'] == "default")
                                    @php
                                        $t1ItemPath = trim((string) parse_url(url(trim($menu_item['url'])), PHP_URL_PATH), '/');
                                        $t1Active = $t1ItemPath === $t1CurrentPath;
                                    @endphp
                                    <li>
                                        <a href="{{url(trim($menu_item['url']))}}"
                                           class="nav-link font-th small{{ $t1Active ? ' is-active' : '' }}"
                                           @if($t1Active) aria-current="page" @endif>{{$menu_item['title']}}</a>
                                    </li>
                                @elseif($menu_item['type'] == "service")
                                    @php
                                        $t1Active = request()->routeIs('service.*');
                                    @endphp
                                    @if($menu_item['sidebyside'] == "no" && $menu_services)
                                        @php
                                            $t1HasKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_services);
                                        @endphp
                                        <li class="{{ $t1HasKids ? 't1-nav-hover' : '' }}">
                                            <a href="{{route('service.list')}}"
                                               class="nav-link font-th small d-flex align-items-center{{ $t1Active ? ' is-active' : '' }}"
                                               @if($t1HasKids) aria-haspopup="true" @endif
                                               @if($t1Active) aria-current="page" @endif>
                                                {{$menu_item['title']}}
                                                @if($t1HasKids)
                                                    <i class="bi bi-chevron-down t1-nav__caret" aria-hidden="true"></i>
                                                @endif
                                            </a>
                                            @if($t1HasKids)
                                                @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.hover-drop", ['menu_items' => $menu_services, 'route_name' => 'service.detail'])
                                            @endif
                                        </li>
                                    @elseif($menu_item['sidebyside'] == "yes" && $menu_services)

                                        @foreach($menu_services as $menu_service_key => $menu_service)
                                            @php
                                                $t1HasKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_service['childrenInMenu'] ?? null);
                                            @endphp
                                            <li class="{{ $t1HasKids ? 't1-nav-hover' : '' }}">
                                                <a href="{{ route('service.detail', ['url' => $menu_service['url']]) }}"
                                                   class="nav-link font-th small d-flex align-items-center"
                                                   @if($t1HasKids) aria-haspopup="true" @endif>
                                                    {{$menu_service['title']}}
                                                    @if($t1HasKids)
                                                        <i class="bi bi-chevron-down t1-nav__caret" aria-hidden="true"></i>
                                                    @endif
                                                </a>
                                                @if($t1HasKids)
                                                    @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.hover-drop", ['menu_items' => $menu_service['childrenInMenu'], 'route_name' => 'service.detail'])
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                @elseif($menu_item['type'] == "product")
                                    @php
                                        $t1Active = request()->routeIs('category.*', 'product.*');
                                    @endphp
                                    @if(@$menu_item['sidebyside'] == "no" && $menu_product_categories)
                                        @php
                                            $t1HasKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_product_categories);
                                        @endphp
                                        <li class="{{ $t1HasKids ? 't1-nav-hover' : '' }}">
                                            <a href="{{route('category.list')}}"
                                               class="nav-link font-th small d-flex align-items-center{{ $t1Active ? ' is-active' : '' }}"
                                               @if($t1HasKids) aria-haspopup="true" @endif
                                               @if($t1Active) aria-current="page" @endif>
                                                {{$menu_item['title']}}
                                                @if($t1HasKids)
                                                    <i class="bi bi-chevron-down t1-nav__caret" aria-hidden="true"></i>
                                                @endif
                                            </a>
                                            @if($t1HasKids)
                                                @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.hover-drop", ['menu_items' => $menu_product_categories, 'route_name' => 'category.detail'])
                                            @endif
                                        </li>
                                    @elseif(@$menu_item['sidebyside'] == "yes" && $menu_product_categories)
                                        @foreach($menu_product_categories as $menu_product_category_key => $menu_product_category)
                                            @php
                                                $t1HasKids = \App\Modules\Setting\Helper\MenuHelper::hasChildren($menu_product_category['childrenInMenu'] ?? null);
                                            @endphp
                                            <li class="{{ $t1HasKids ? 't1-nav-hover' : '' }}">
                                                <a href="{{ \App\Library\SiteUrl::category($menu_product_category) }}"
                                                   class="nav-link font-th small d-flex align-items-center"
                                                   @if($t1HasKids) aria-haspopup="true" @endif>
                                                    {{$menu_product_category['title']}}
                                                    @if($t1HasKids)
                                                        <i class="bi bi-chevron-down t1-nav__caret" aria-hidden="true"></i>
                                                    @endif
                                                </a>
                                                @if($t1HasKids)
                                                    @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.hover-drop", ['menu_items' => $menu_product_category['childrenInMenu'], 'route_name' => 'category.detail'])
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                    <div class="t1-header-tools">
                        @if($theme_provider->hasSection('siteSections','search'))
                            @include("layouts.main.blocks." . $theme_provider->getValue() . ".search")
                        @endif
                        <ul class="t1-header-tools__cart p-0 m-0" id="menu">
                        @if($settings['disable_shop'] == 0)
                            <li class="position-relative">
                                <a href="{{route('basket.cart')}}"
                                   class="t1-header-icon" aria-label="سبد خرید">
                                    <i class="bi bi-bag" aria-hidden="true"></i>
                                </a>
                                <span class="cart-num font-num-r">@{{ basketItemCount }}</span>
                            </li>
                        @endif
                        </ul>
                    </div>
                </div>
                </div>
            </div>
            @endmobile
</section>
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
    @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu-app")
@endif
@include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.menu-sidebar")
@endmobile

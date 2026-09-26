@php
    use App\Modules\Setting\Helper\MenuHelper;

    $menuLinks = $settings['menu_links'] ?? [];
    $productCategories = $menu_product_categories ?? [];
    $services = $menu_services ?? [];
    $hasProductGroup = false;
    $hasServiceGroup = false;
    foreach ($menuLinks as $menuLink) {
        if (($menuLink['type'] ?? '') === 'product' && ($menuLink['sidebyside'] ?? '') === 'no') {
            $hasProductGroup = true;
        }
        if (($menuLink['type'] ?? '') === 'service' && ($menuLink['sidebyside'] ?? '') === 'no') {
            $hasServiceGroup = true;
        }
    }
    $listClass = $listClass ?? 'menu-drawer__list';
    $itemClass = $itemClass ?? 'menu-drawer__item';
    $linkClass = $linkClass ?? 'menu-drawer__link';
    $btnClass = $btnClass ?? 'js-menu-drill menu-drawer__link menu-drawer__link--btn';
    $labelClass = $labelClass ?? 'menu-drawer__label';
@endphp

<div class="menu-steps">
    <div class="step active" data-step="1">
        <ul class="{{ $listClass }}">
            @foreach ($menuLinks as $item)
                @if (($item['type'] ?? '') == 'default')
                    <li class="{{ $itemClass }}">
                        <a class="{{ $linkClass }}" href="{{ url(trim($item['url'])) }}">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @elseif (($item['type'] ?? '') == 'product')
                    @if (($item['sidebyside'] ?? '') == 'no')
                        @if (MenuHelper::hasChildren($productCategories))
                            <li class="{{ $itemClass }}">
                                <button type="button" class="{{ $btnClass }}"
                                        data-next="product_main"
                                        data-title="{{ $item['title'] }}"
                                        data-url="{{ route('category.list') }}">
                                    <span class="{{ $labelClass }}">{{ $item['title'] }}</span>
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </button>
                            </li>
                        @else
                            <li class="{{ $itemClass }}">
                                <a class="{{ $linkClass }}" href="{{ route('category.list') }}">
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endif
                    @elseif (($item['sidebyside'] ?? '') == 'yes' && $productCategories)
                        @foreach ($productCategories as $cat)
                            @php
                                $catId = $cat->id ?? ($cat['id'] ?? null);
                                $catTitle = $cat->title ?? ($cat['title'] ?? '');
                                $catUrl = $cat->url ?? ($cat['url'] ?? '');
                                $catKids = $cat['childrenInMenu'] ?? [];
                            @endphp
                            <li class="{{ $itemClass }}">
                                @if (MenuHelper::hasChildren($catKids))
                                    <button type="button" class="{{ $btnClass }}"
                                            data-next="cat_{{ $catId }}"
                                            data-title="{{ $catTitle }}"
                                            data-url="{{ \App\Library\SiteUrl::category($cat) }}">
                                        <span class="{{ $labelClass }}">{{ $catTitle }}</span>
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <a class="{{ $linkClass }}" href="{{ \App\Library\SiteUrl::category($cat) }}">
                                        {{ $catTitle }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @elseif (($item['type'] ?? '') == 'service')
                    @if (($item['sidebyside'] ?? '') == 'no')
                        @if (MenuHelper::hasChildren($services))
                            <li class="{{ $itemClass }}">
                                <button type="button" class="{{ $btnClass }}"
                                        data-next="service_main"
                                        data-title="{{ $item['title'] }}"
                                        data-url="{{ route('service.list') }}">
                                    <span class="{{ $labelClass }}">{{ $item['title'] }}</span>
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </button>
                            </li>
                        @else
                            <li class="{{ $itemClass }}">
                                <a class="{{ $linkClass }}" href="{{ route('service.list') }}">
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endif
                    @elseif (($item['sidebyside'] ?? '') == 'yes' && $services)
                        @foreach ($services as $service)
                            @php
                                $serId = $service->id ?? ($service['id'] ?? null);
                                $serTitle = $service->title ?? ($service['title'] ?? '');
                                $serUrl = $service->url ?? ($service['url'] ?? '');
                                $serKids = $service['childrenInMenu'] ?? [];
                            @endphp
                            <li class="{{ $itemClass }}">
                                @if (MenuHelper::hasChildren($serKids))
                                    <button type="button" class="{{ $btnClass }}"
                                            data-next="ser_{{ $serId }}"
                                            data-title="{{ $serTitle }}"
                                            data-url="{{ route('service.detail', ['url' => $serUrl]) }}">
                                        <span class="{{ $labelClass }}">{{ $serTitle }}</span>
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <a class="{{ $linkClass }}" href="{{ route('service.detail', ['url' => $serUrl]) }}">
                                        {{ $serTitle }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endif
            @endforeach
        </ul>
    </div>

    @if ($hasProductGroup && MenuHelper::hasChildren($productCategories))
        <div class="step" data-step="product_main">
            <ul class="{{ $listClass }}">
                @foreach ($productCategories as $cat)
                    @php
                        $catId = $cat->id ?? ($cat['id'] ?? null);
                        $catTitle = $cat->title ?? ($cat['title'] ?? '');
                        $catUrl = $cat->url ?? ($cat['url'] ?? '');
                        $catKids = $cat['childrenInMenu'] ?? [];
                    @endphp
                    <li class="{{ $itemClass }}">
                        @if (MenuHelper::hasChildren($catKids))
                            <button type="button" class="{{ $btnClass }}"
                                    data-next="cat_{{ $catId }}"
                                    data-title="{{ $catTitle }}"
                                    data-url="{{ \App\Library\SiteUrl::category($cat) }}">
                                <span class="{{ $labelClass }}">{{ $catTitle }}</span>
                                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            </button>
                        @else
                            <a class="{{ $linkClass }}" href="{{ \App\Library\SiteUrl::category($cat) }}">
                                {{ $catTitle }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($hasServiceGroup && MenuHelper::hasChildren($services))
        <div class="step" data-step="service_main">
            <ul class="{{ $listClass }}">
                @foreach ($services as $service)
                    @php
                        $serId = $service->id ?? ($service['id'] ?? null);
                        $serTitle = $service->title ?? ($service['title'] ?? '');
                        $serUrl = $service->url ?? ($service['url'] ?? '');
                        $serKids = $service['childrenInMenu'] ?? [];
                    @endphp
                    <li class="{{ $itemClass }}">
                        @if (MenuHelper::hasChildren($serKids))
                            <button type="button" class="{{ $btnClass }}"
                                    data-next="ser_{{ $serId }}"
                                    data-title="{{ $serTitle }}"
                                    data-url="{{ route('service.detail', ['url' => $serUrl]) }}">
                                <span class="{{ $labelClass }}">{{ $serTitle }}</span>
                                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            </button>
                        @else
                            <a class="{{ $linkClass }}" href="{{ route('service.detail', ['url' => $serUrl]) }}">
                                {{ $serTitle }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach ($productCategories as $cat)
        @if (MenuHelper::hasChildren($cat['childrenInMenu'] ?? null))
            <div class="step" data-step="cat_{{ $cat->id ?? $cat['id'] }}">
                <ul class="{{ $listClass }}">
                    @foreach ($cat['childrenInMenu'] as $sub)
                        <li class="{{ $itemClass }}">
                            @if (MenuHelper::hasChildren($sub['childrenInMenu'] ?? null))
                                <button type="button" class="{{ $btnClass }}"
                                        data-next="sub_cat_{{ $sub['id'] ?? ($sub->id ?? '') }}"
                                        data-title="{{ $sub['title'] }}"
                                        data-url="{{ \App\Library\SiteUrl::category($sub) }}">
                                    <span class="{{ $labelClass }}">{{ $sub['title'] }}</span>
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </button>
                            @else
                                <a class="{{ $linkClass }}" href="{{ \App\Library\SiteUrl::category($sub) }}">
                                    {{ $sub['title'] }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @foreach ($cat['childrenInMenu'] as $sub)
                @if (MenuHelper::hasChildren($sub['childrenInMenu'] ?? null))
                    <div class="step" data-step="sub_cat_{{ $sub['id'] ?? ($sub->id ?? '') }}">
                        <ul class="{{ $listClass }}">
                            @foreach ($sub['childrenInMenu'] as $subChild)
                                <li class="{{ $itemClass }}">
                                    <a class="{{ $linkClass }}" href="{{ \App\Library\SiteUrl::category($subChild) }}">
                                        {{ $subChild['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        @endif
    @endforeach

    @foreach ($services as $service)
        @if (MenuHelper::hasChildren($service['childrenInMenu'] ?? null))
            <div class="step" data-step="ser_{{ $service->id ?? $service['id'] }}">
                <ul class="{{ $listClass }}">
                    @foreach ($service['childrenInMenu'] as $subSer)
                        <li class="{{ $itemClass }}">
                            @if (MenuHelper::hasChildren($subSer['childrenInMenu'] ?? null))
                                <button type="button" class="{{ $btnClass }}"
                                        data-next="sub_ser_{{ $subSer['id'] ?? ($subSer->id ?? '') }}"
                                        data-title="{{ $subSer['title'] }}"
                                        data-url="{{ route('service.detail', ['url' => $subSer['url']]) }}">
                                    <span class="{{ $labelClass }}">{{ $subSer['title'] }}</span>
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                </button>
                            @else
                                <a class="{{ $linkClass }}" href="{{ route('service.detail', ['url' => $subSer['url']]) }}">
                                    {{ $subSer['title'] }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @foreach ($service['childrenInMenu'] as $subSer)
                @if (MenuHelper::hasChildren($subSer['childrenInMenu'] ?? null))
                    <div class="step" data-step="sub_ser_{{ $subSer['id'] ?? ($subSer->id ?? '') }}">
                        <ul class="{{ $listClass }}">
                            @foreach ($subSer['childrenInMenu'] as $subSerChild)
                                <li class="{{ $itemClass }}">
                                    <a class="{{ $linkClass }}" href="{{ route('service.detail', ['url' => $subSerChild['url']]) }}">
                                        {{ $subSerChild['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        @endif
    @endforeach
</div>

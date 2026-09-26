@php
    $level = $level ?? 1;
    $items = $menu_items ?? [];
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }
@endphp
@if($items && count($items) > 0)
    <div class="t1-drop{{ $level > 1 ? ' t1-drop--flyout' : '' }}">
        <ul class="t1-drop__list">
            @foreach($items as $drop_item)
                @php
                    $kids = $drop_item['childrenInMenu'] ?? [];
                    if ($kids instanceof \Illuminate\Support\Collection) {
                        $kids = $kids->all();
                    }
                    $hasKids = is_countable($kids) && count($kids) > 0 && $level < 4;
                @endphp
                <li class="t1-drop__item{{ $hasKids ? ' t1-drop__item--parent' : '' }}">
                    <a href="{{ \App\Library\SiteUrl::named($route_name, $drop_item) }}" class="t1-drop__link">
                        {{ $drop_item['title'] }}
                        @if($hasKids)
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        @endif
                    </a>
                    @if($hasKids)
                        @include("layouts.main.blocks." . $theme_provider->getValue() . ".menu_partials.hover-drop", [
                            'menu_items' => $kids,
                            'route_name' => $route_name,
                            'level' => $level + 1,
                        ])
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

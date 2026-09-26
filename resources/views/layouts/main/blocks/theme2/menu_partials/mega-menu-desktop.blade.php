@if($menu_items && count($menu_items) > 0)
    @php
        $megaTotalItems = count($menu_items);
        $megaColumnCount = match (true) {
            $megaTotalItems <= 4 => 2,
            $megaTotalItems <= 9 => 3,
            default => 4,
        };
        $megaColumns = array_fill(0, $megaColumnCount, []);

        foreach ($menu_items as $menuIndex => $menu_item_inner) {
            $targetColumn = $menuIndex % $megaColumnCount;
            $megaColumns[$targetColumn][] = [
                'item' => $menu_item_inner,
                'index' => $menuIndex,
            ];
        }
    @endphp
    <div class="mega-menu__wrap" id="{{ $mega_panel_id ?? 'productsMega' }}" data-mega-panel="{{ $mega_panel_id ?? 'productsMega' }}">
        <div class="mega-menu mega-menu--hover-sub">
            <div class="mega-menu__grid">
                <div class="mega-menu__columns">
                    @foreach ($megaColumns as $megaColumn)
                        @if (count($megaColumn))
                            <div class="mega-menu__column">
                                @foreach ($megaColumn as $megaEntry)
                                    @php
                                        $menu_item_inner = $megaEntry['item'];
                                        $megaChildren = data_get($menu_item_inner, 'childrenInMenu', []);
                                        $megaHasChildren = $megaChildren instanceof \Illuminate\Support\Collection
                                            ? $megaChildren->isNotEmpty()
                                            : (is_countable($megaChildren) && count($megaChildren) > 0);
                                    @endphp
                                    <article class="mega-menu__item{{ $megaHasChildren ? ' mega-menu__item--has-children' : '' }}">
                                        <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_inner) }}"
                                            class="mega-menu__link text-dark d-flex align-items-center font-bold">
                                            <span class="line-icon me-2"></span>
                                            <span class="mega-menu__link-text">{{ data_get($menu_item_inner, 'title') }}</span>
                                            @if ($megaHasChildren)
                                                <i class="bi bi-chevron-down d-flex ms-auto mega-menu__link-chevron"></i>
                                            @endif
                                        </a>
                                        @if ($megaHasChildren)
                                            <div class="mega-menu__flyout" aria-hidden="true">
                                                <ul class="p-0 m-0 mega-menu__flyout-list">
                                                    @foreach ($megaChildren as $menu_item_child)
                                                        <li class="list-unstyled mega-menu__flyout-item">
                                                            <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_child) }}"
                                                                class="mega-menu__flyout-link d-flex align-items-center font-re">
                                                                <i class="bi bi-chevron-left d-flex mega-menu__flyout-icon"></i>
                                                                {{ data_get($menu_item_child, 'title') }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

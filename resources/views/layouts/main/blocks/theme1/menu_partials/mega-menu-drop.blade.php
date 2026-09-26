@if($menu_items && count($menu_items) > 0)
    <div class="drop-box-mega t1-mega" id="parent">
        <ul class="p-0 m-0 drop-box-mega-ul" id="children">
            @foreach($menu_items as $menu_item_inner)
                <li class="menu-item t1-mega__group p-1 position-relative p-0 m-0 mb-3" >
                    <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_inner) }}" class="t1-mega__link d-flex align-items-center">
                                                <i class="bi bi-arrow-left-short d-flex t1-mega__caret"></i>
                        {{$menu_item_inner['title']}}
                    </a>

                    @if(isset($menu_item_inner['childrenInMenu']) && count($menu_item_inner['childrenInMenu']) > 0)
                        <ul class="children-menu t1-mega__sublist p-0 m-0 mt-1">
                            @foreach($menu_item_inner['childrenInMenu'] as $menu_item_child)
                                <li class="list-unstyled m-0 py-1 ms-1 small">
                                    <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_child) }}" class="sub-cat t1-mega__sublink d-flex align-items-center font-th">
                                        {{$menu_item_child['title']}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach

        </ul>
        <div class="scrollGuide t1-mega__guide d-none" id="guide">
            <i class="bi bi-chevron-double-down d-flex"></i>
        </div>
    </div>
@endif




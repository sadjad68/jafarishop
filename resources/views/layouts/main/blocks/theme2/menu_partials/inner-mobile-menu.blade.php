
@if($menu_items && count($menu_items) > 0)

                <ul class="p-0 m-0">
                    <div class="accordion" id="accordionExample{{$main_route}}">
                        @foreach($menu_items as $menu_item_inner)
                            @if(isset($menu_item_inner['childrenInMenu']) && count($menu_item_inner['childrenInMenu']) > 0)
                        <div class="accordion-item site-menu-drawer__accordion border-0 m-0">
                            <div class="accordion-header">
                                <button class="accordion-button collapsed shadow-none site-menu-drawer__accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$main_route.$menu_item_inner['id']}}" aria-expanded="false" aria-controls="collapse{{$main_route.$menu_item_inner['id']}}">
                                    <i class="bi bi-folder2-open d-flex site-menu-drawer__link-icon"></i>
                                    {{$menu_item_inner['title']}}
                                </button>
                            </div>
                            <div id="collapse{{$main_route.$menu_item_inner['id']}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample{{$main_route}}">
                                <div class="accordion-body site-menu-drawer__accordion-body p-2 mb-3">
                                    <ul class="m-0 p-0">
                                        <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_inner) }}" class="d-flex align-items-center small font-bold main-link">
                                            <i class="bi bi-caret-left-fill d-flex me-1"></i>
                                            مشاهده {{$menu_item_inner['title']}}
                                        </a>
                                        <ul class="p-0 m-0 mt-2">
                                            @foreach($menu_item_inner['childrenInMenu'] as $menu_item_child)
                                                <li class="list-unstyled link-child">
                                                    <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_child) }}" class="d-flex align-items-center">
                                                        <i class="bi bi-dot d-flex me-1"></i>
                                                        {{$menu_item_child['title']}}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                            @else
                                <li class="list-unstyled m-0 site-menu-drawer__item">
                                    <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_inner) }}" class="d-flex align-items-center tak-link site-menu-drawer__link text-dark">
                                        <i class="bi bi-tag d-flex site-menu-drawer__link-icon"></i>
                                        {{$menu_item_inner['title']}}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </div>
                </ul>

@else
    <li class="border-bottom">
        <a href="{{route($main_route.'.list')}}"
           class="nav-link px-3 py-2">
            {{$menu_item['title']}}
        </a>
    </li>
@endif

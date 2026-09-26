
@if($menu_items && count($menu_items) > 0)

                <ul class="p-0 m-0 t1-mobile-nav__sublist">
                    @foreach($menu_items as $menu_item_inner)
                        @if(isset($menu_item_inner['childrenInMenu']) && count($menu_item_inner['childrenInMenu']) > 0)

                        <li class="list-unstyled m-0 t1-mobile-nav__item">
                            <div class="accordion accordion-flush lvl2"
                                 id="accordionFlushExample{{$main_route}}">
                                <div
                                    class="accordion-item bg-transparent border-0 mb-0">
                                    <p class="accordion-header">
                                        <button
                                            class="accordion-button t1-mobile-nav__link bg-transparent collapsed d-flex align-items-center justify-content-between border-0 shadow-none"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#accordionFlushExample{{$main_route.$menu_item_inner['id']}}"
                                            aria-expanded="false"
                                            aria-controls="accordionFlushExample{{$main_route.$menu_item_inner['id']}}">
                                            {{$menu_item_inner['title']}}
                                        </button>
                                    </p>
                                    <div id="accordionFlushExample{{$main_route.$menu_item_inner['id']}}"
                                         class="accordion-collapse collapse"
                                         data-bs-parent="#accordionFlushExample{{$main_route}}">
                                        <div
                                            class="accordion-body t1-mobile-nav__accordion-body">
                                            <ul class="m-0 p-0">
                                                <li class="list-unstyled m-0">
                                                    <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_inner) }}"
                                                       class="t1-mobile-nav__sublink t1-mobile-nav__sublink--all d-flex align-items-center">
                                                        مشاهده همه {{$menu_item_inner['title']}}
                                                    </a>
                                                </li>
                                                @foreach($menu_item_inner['childrenInMenu'] as $menu_item_child)
                                                <li class="list-unstyled m-0">
                                                    <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_child) }}"
                                                       class="t1-mobile-nav__sublink d-flex align-items-center">
                                                        {{$menu_item_child['title']}}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        @else
                    <li class="list-unstyled m-0 t1-mobile-nav__item">
                        <a href="{{ \App\Library\SiteUrl::named($route_name, $menu_item_inner) }}" class="t1-mobile-nav__link d-flex align-items-center">
                           {{$menu_item_inner['title']}}
                        </a>
                    </li>
                        @endif
                    @endforeach
                </ul>

@else
    <li class="t1-mobile-nav__item">
        <a href="{{route($main_route.'.list')}}"
           class="nav-link t1-mobile-nav__link d-flex align-items-center">
            {{$menu_item['title']}}
        </a>
    </li>
@endif

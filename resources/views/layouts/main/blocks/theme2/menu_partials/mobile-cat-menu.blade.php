@php
    use App\Modules\Setting\Helper\MenuHelper;

    $firstNestedCatIndex = $firstNestedCatIndex ?? 0;
@endphp
<div class="offcanvas offcanvas-bottom mob-cat-offcanvas h-100 rounded-0 border-0" tabindex="-1" id="offcanvasCat"
    aria-labelledby="offcanvasCatLabel">
    <div class="offcanvas-header border-bottom py-2 px-3">
        <div class="w-100 d-flex justify-content-between align-items-center">
            <div class="mob-cat-placeholder"></div>
            <img src="{{ $settings['logo'] ?? asset('assets/site/images/logo.png') }}"
                alt="{{ $settings['siteName_fa'] ?? '' }}" class="mob-cat-logo">
            <button type="button" class="btn p-1 border-0" data-bs-dismiss="offcanvas" aria-label="بستن">
                <i class="bi bi-x-lg fs-4 text-secondary"></i>
            </button>
        </div>
    </div>

    <div class="offcanvas-body p-0 d-flex flex-row mob-cat-body">
        <div class="mob-cat-col-right" id="mobCatTabs">
            <ul class="list-unstyled m-0 p-0">
                @if (isset($menu_product_categories) && count($menu_product_categories))
                    @foreach ($menu_product_categories as $index => $cat)
                        @php
                            $catKids = $cat['childrenInMenu'] ?? [];
                            $catHasKids = MenuHelper::hasChildren($catKids);
                        @endphp
                        <li class="mob-cat-l1-item {{ $catHasKids && $index === $firstNestedCatIndex ? 'active' : '' }}"
                            @if ($catHasKids) data-target="mob-cat-content-{{ $cat->id ?? $cat['id'] }}" @endif>
                            @if ($catHasKids)
                                <button type="button"
                                    class="d-flex flex-column align-items-center border-0 bg-transparent py-3 px-1 w-100 h-100">
                                    <div
                                        class="icon-wrap bg-white rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm mob-cat-icon-wrap">
                                        @if ($cat->icon ?? ($cat['icon'] ?? null))
                                            <img src="{{ $cat->icon ?? $cat['icon'] }}" width="24" height="24"
                                                class="mob-cat-logo" alt="">
                                        @else
                                            <i class="bi bi-tag text-secondary fs-6"></i>
                                        @endif
                                    </div>
                                    <span class="text-center text-secondary mob-cat-l1-title">
                                        {{ $cat->title ?? $cat['title'] }}
                                    </span>
                                </button>
                            @else
                                <a href="{{ \App\Library\SiteUrl::category($cat) }}"
                                    class="d-flex flex-column align-items-center text-decoration-none py-3 px-1 w-100 h-100">
                                    <div
                                        class="icon-wrap bg-white rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm mob-cat-icon-wrap">
                                        @if ($cat->icon ?? ($cat['icon'] ?? null))
                                            <img src="{{ $cat->icon ?? $cat['icon'] }}" width="24" height="24"
                                                class="mob-cat-logo" alt="">
                                        @else
                                            <i class="bi bi-tag text-secondary fs-6"></i>
                                        @endif
                                    </div>
                                    <span class="text-center text-secondary mob-cat-l1-title">
                                        {{ $cat->title ?? $cat['title'] }}
                                    </span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li class="px-2 py-4 text-center text-secondary small">دسته‌بندی‌ای وجود ندارد</li>
                @endif
            </ul>
        </div>

        <div class="mob-cat-col-left flex-grow-1 bg-white" id="mobCatContents">
            @if (isset($menu_product_categories) && count($menu_product_categories))
                @foreach ($menu_product_categories as $index => $cat)
                    @php
                        $catKids = $cat['childrenInMenu'] ?? [];
                    @endphp
                    @if (MenuHelper::hasChildren($catKids))
                        <div class="mob-cat-content-pane {{ $index === $firstNestedCatIndex ? 'd-block' : 'd-none' }} w-100"
                            id="mob-cat-content-{{ $cat->id ?? $cat['id'] }}">
                            <div
                                class="d-flex justify-content-between align-items-center py-3 px-3 border-bottom bg-white sticky-top mob-cat-sticky-header">
                                <span class="text-secondary d-flex align-items-center gap-2 mob-cat-sticky-title">
                                    همه دسته‌بندی‌ها
                                    <i class="bi bi-chevron-left text-muted mob-cat-sticky-icon"></i>
                                </span>
                                <a href="{{ \App\Library\SiteUrl::category($cat) }}"
                                    class="text-decoration-none mob-cat-sticky-link">
                                    همه محصولات
                                </a>
                            </div>

                            <div class="px-3 pb-3 pt-2">
                                <h6 class="fw-bold mb-3 mt-1 text-dark mob-cat-group-title">
                                    {{ $cat->title ?? $cat['title'] }}
                                </h6>

                                @php $catAccordionId = 'mob-cat-accordion-' . ($cat->id ?? $cat['id']); @endphp
                                <div id="{{ $catAccordionId }}">
                                    @foreach ($catKids as $sub)
                                        @php
                                            $uniqueId = ($cat->id ?? $cat['id']) . '_' . ($sub['id'] ?? $loop->index);
                                            $subKids = $sub['childrenInMenu'] ?? [];
                                        @endphp
                                        @if (MenuHelper::hasChildren($subKids))
                                            <div class="sub-group-box mb-3 w-100 overflow-hidden mob-cat-sub-box">
                                                <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none mob-cat-sub-toggle mob-cat-collapse-toggle"
                                                    data-bs-toggle="collapse"
                                                    href="#mob-collapse-{{ $uniqueId }}" role="button"
                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                    aria-controls="mob-collapse-{{ $uniqueId }}">
                                                    <span class="mob-cat-sub-title">
                                                        {{ $sub['title'] }}
                                                    </span>
                                                    <i class="bi bi-chevron-down collapse-icon"></i>
                                                </a>

                                                <div class="collapse {{ $loop->first ? 'show' : '' }}"
                                                    id="mob-collapse-{{ $uniqueId }}"
                                                    data-bs-parent="#{{ $catAccordionId }}">
                                                    <div class="row g-2 p-3">
                                                        @foreach ($subKids as $sub_child)
                                                            <div class="col-4 d-flex flex-column align-items-center">
                                                                <a href="{{ \App\Library\SiteUrl::category($sub_child) }}"
                                                                    class="text-decoration-none d-flex flex-column align-items-center w-100">
                                                                    <div
                                                                        class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-2 border mob-cat-item-bubble">
                                                                        @if ($sub_child['icon'] ?? null)
                                                                            <img src="{{ $sub_child['icon'] }}"
                                                                                class="mob-cat-item-img" alt="">
                                                                        @else
                                                                            <i class="bi bi-tag text-muted"></i>
                                                                        @endif
                                                                    </div>
                                                                    <span
                                                                        class="text-dark text-center mob-cat-item-title">{{ $sub_child['title'] }}</span>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                        <div class="col-4 d-flex flex-column align-items-center">
                                                            <a href="{{ \App\Library\SiteUrl::category($sub) }}"
                                                                class="text-decoration-none d-flex flex-column align-items-center w-100">
                                                                <div
                                                                    class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-2 border mob-cat-item-bubble">
                                                                    <i class="bi bi-grid text-secondary fs-4"></i>
                                                                </div>
                                                                <span class="text-dark text-center mob-cat-item-title">همه
                                                                    کالاها</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ \App\Library\SiteUrl::category($sub) }}"
                                                class="d-flex align-items-center text-dark text-decoration-none mob-cat-sub-box mob-cat-sub-leaf px-3 py-3 mb-3">
                                                <span class="mob-cat-sub-title">{{ $sub['title'] }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabs = document.querySelectorAll('#mobCatTabs .mob-cat-l1-item[data-target]');
            var contents = document.querySelectorAll('#mobCatContents .mob-cat-content-pane');

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    tabs.forEach(function(t) {
                        t.classList.remove('active');
                    });
                    contents.forEach(function(c) {
                        c.classList.remove('d-block');
                        c.classList.add('d-none');
                    });

                    tab.classList.add('active');
                    var targetId = tab.getAttribute('data-target');
                    var targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.classList.remove('d-none');
                        targetContent.classList.add('d-block');
                        targetContent.scrollTop = 0;
                    }
                });
            });
        });
    </script>
@endpush

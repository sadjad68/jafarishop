<div class="offcanvas offcanvas-start site-menu-drawer" tabindex="-1" id="offcanvasExample"
    aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header site-menu-drawer__header">
        <img src="{{ $settings['logo'] }}" width="90" height="auto" loading="lazy"
            alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="site-menu-drawer__logo" />
        <button type="button" class="site-menu-drawer__close btn btn-text text-dark p-0 border-0" data-bs-dismiss="offcanvas"
            aria-label="بستن">
            <i class="bi bi-x-lg d-flex fs-4"></i>
        </button>
    </div>
    <div class="offcanvas-body site-menu-drawer__body p-2">
        <div class="mobile-menu mobile-menu-container">
            <div class="menu-header site-mobile-menu__header" hidden>
                <div class="panel-bg d-flex justify-content-between p-2 w-100">
                    <button type="button" class="back-btn back d-flex align-items-center gap-1 border-0 bg-transparent">
                        <i class="bi bi-arrow-right fs-5 d-flex me-2"></i>
                        بازگشت
                    </button>
                    <a href="#" class="text-decoration-none text-black-50 menu-title-link">
                        <span class="menu-title"></span>
                    </a>
                </div>
            </div>
            @include('layouts.main.blocks.menu_partials.menu-drawer-steps', [
                'listClass' => 'p-0 m-0 list-unstyled',
                'itemClass' => 'nav-item p-3 border-bottom site-mobile-menu__item',
                'linkClass' => 'fs-6 d-flex align-items-center text-decoration-none text-dark',
                'btnClass' => 'js-menu-drill fs-6 d-flex justify-content-between align-items-center gap-2 p-0 border-0 bg-transparent w-100 text-dark text-start',
                'labelClass' => 'd-flex align-items-center',
            ])
        </div>
    </div>
</div>

@if (!empty($hasNestedProductCats))
    @include('layouts.main.blocks.theme2.menu_partials.mobile-cat-menu')
@endif

@include('layouts.main.blocks.menu_partials.mobile-menu-script')

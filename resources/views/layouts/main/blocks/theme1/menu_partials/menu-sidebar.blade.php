<div class="offcanvas offcanvas-start t1-drawer" tabindex="-1" id="offcanvasExample"
     aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header t1-drawer__head">
        <img src="{{ $settings['logo'] }}" width="90" height="auto" loading="lazy"
             alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="t1-drawer__logo">
        <button type="button" class="t1-drawer__close" data-bs-dismiss="offcanvas" aria-label="بستن منو">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>
    </div>
    <div class="offcanvas-body t1-drawer__body">
        <div class="mobile-menu-container t1-drawer__menu">
            <div class="menu-header t1-drawer__crumb" hidden>
                <div class="t1-drawer__crumb-row">
                    <button type="button" class="back-btn t1-drawer__back">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        بازگشت
                    </button>
                    <a href="#" class="t1-drawer__crumb-link menu-title-link">
                        <span class="menu-title"></span>
                    </a>
                </div>
            </div>
            @include('layouts.main.blocks.menu_partials.menu-drawer-steps', [
                'listClass' => 't1-drawer__list',
                'itemClass' => 't1-drawer__item',
                'linkClass' => 't1-drawer__link',
                'btnClass' => 'js-menu-drill t1-drawer__link t1-drawer__link--btn',
                'labelClass' => 't1-drawer__link-main',
            ])
        </div>
    </div>
</div>

@if (($settings['disable_shop'] ?? 1) == 0 && !empty($hasNestedProductCats))
    @include('layouts.main.blocks.theme2.menu_partials.mobile-cat-menu')
@endif

@include('layouts.main.blocks.menu_partials.mobile-menu-script')

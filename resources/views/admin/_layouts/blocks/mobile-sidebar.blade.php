<div class="admin-topbar admin-topbar-mobile d-flex d-lg-none">
    <button class="admin-icon-btn" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasderawer" aria-controls="offcanvasderawer" title="منو">
        <i class="bi bi-list"></i>
    </button>
    @include('admin._layouts.blocks.header-tools', ['notifyId' => 'drop-alert2', 'includeLogout' => true])
</div>
<div class="offcanvas offcanvas-start admin-drawer" tabindex="-1" id="offcanvasderawer"
    aria-labelledby="offcanvasderawerLabel">
    <div class="admin-sidebar-head">
        @include('admin._layouts.blocks.sidebar-brand', ['brandId' => 'offcanvasderawerLabel'])
        <button type="button" class="admin-icon-btn" data-bs-dismiss="offcanvas" aria-label="بستن منو">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="offcanvas-body">
            @include('admin._layouts.blocks.inner-sidebar')
</div>
</div>

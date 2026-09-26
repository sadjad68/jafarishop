<div class="d-none d-lg-block">
    <aside class="sidebar admin-sidebar" id="sidebar">
        <div class="content d-flex flex-column h-100">
            <div class="admin-sidebar-head">
                @include('admin._layouts.blocks.sidebar-brand')
                <button type="button" class="admin-icon-btn admin-sidebar-toggle" id="openMenu" title="جمع کردن منو" aria-label="جمع کردن منو">
                    <i class="bi bi-layout-sidebar-inset"></i>
                </button>
            </div>
           @include('admin._layouts.blocks.inner-sidebar')
        </div>
    </aside>
</div>

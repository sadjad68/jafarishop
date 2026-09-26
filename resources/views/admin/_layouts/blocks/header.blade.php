<div class="admin-topbar d-none d-lg-flex">
    @include('admin._layouts.blocks.header-tools', ['notifyId' => 'drop-alert'])
    <a class="admin-icon-btn admin-icon-btn-logout" href="{{ url('admin/logout') }}" data-bs-toggle="tooltip" data-bs-title="خروج">
        <i class="bi bi-power"></i>
    </a>
</div>

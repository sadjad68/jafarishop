<!doctype html>
<html class="no-js" lang="fa" data-theme="dark">
    @include('admin._layouts.blocks.head')
    <body class="admin-glass" style="direction: rtl !important;">
    <style>
        .swal2-container {
            z-index: 11111111111 !important;
        }
    </style>
    @include('admin.components.sweetalert')
        <div class="admin-bg" aria-hidden="true">
            <div class="admin-aurora"></div>
            <div class="admin-blob admin-blob-a"></div>
            <div class="admin-blob admin-blob-b"></div>
            <div class="admin-blob admin-blob-c"></div>
            <div class="admin-noise"></div>
        </div>
        <div id="ebazar-layout" class="theme-blue">
            @include('admin._layouts.blocks.sidebar')
            <div class="main px-lg-4 px-md-4">
                @include('admin._layouts.blocks.mobile-sidebar')
                @include('admin._layouts.blocks.header')
                @yield('content')
            </div>
        </div>
    @include('admin._layouts.blocks.script')
    </body>
</html>

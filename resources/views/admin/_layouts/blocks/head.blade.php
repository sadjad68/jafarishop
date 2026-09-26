<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#050814">
    <script src="{{ asset('assets/admin/js/admin-theme-boot.js') }}"></script>
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/fav.jpg') }}" type="image/x-icon" />
    <title>@yield('title', 'داشبورد')</title>
    <meta name="robots" content=" noindex nofollow" />
    <link href="{{ asset('assets/admin/css/bootstrap.rtl.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.style.min.css?v0.3') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css?v0.46') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.min.css?v0.0') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/perfect-scrollbar.css') }}" />
    <script src="{{ asset('assets/admin/js/axios.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/233bootstrap-select.min.css?v0.01') }}">
    <script src="{{ asset('assets/admin/js/jalali.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jalali.min.js') }}"></script>
    <style>
        .select2 {
            border-width: 1px;
            border-style: solid;
            border-color: #eee;
            border-radius: 4px;
            padding: 4px;
        }

        .select2 span {
            height: 100%;
            display: flex !important;
            align-items: center;
            justify-content: flex-start;
        }

        .select2-results__options {
            height: 10rem;
            display: flex;
            flex-direction: column;
            overflow-y: scroll
        }

        .select2-selection__clear {
            position: absolute;
            font-size: 18px !important;
            left: 8px;
            top: 2px;
            bottom: 0;
        }

        .sidebar .menu-list .m-link span {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    </style>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-glass.css?v1.30') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-ui.css?v1.31') }}" />
</head>

<style>
    .image-container .dropdown {
        width: 100% !important;
        margin: 0 !important;
        margin-top: 15px !important
    }

    .body .container-fluid .card {
        padding: 0 !important;
    }

    .card-block .col-12 ul {
        padding: 0 !important;
    }
</style>

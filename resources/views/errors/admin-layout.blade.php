<!DOCTYPE html>
<html class="no-js" lang="fa" dir="rtl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title') | پنل مدیریت</title>
    <script src="{{ asset('assets/admin/js/admin-theme-boot.js') }}"></script>
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/fav.jpg') }}" type="image/x-icon" />
    <link href="{{ asset('assets/admin/css/bootstrap.rtl.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-glass.css?v0.3') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-ui.css?v0.3') }}" />
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-site-error.css?v1.0') }}" />
</head>
<body class="admin-glass">
    <div class="admin-bg" aria-hidden="true">
        <div class="admin-aurora"></div>
        <div class="admin-blob admin-blob-a"></div>
        <div class="admin-blob admin-blob-b"></div>
        <div class="admin-blob admin-blob-c"></div>
        <div class="admin-noise"></div>
    </div>

    <div class="admin-error-page">
        <div class="admin-error-page__card" role="alert">
            <div class="admin-error-page__code" aria-hidden="true">@yield('code')</div>
            <h1 class="admin-error-page__title">@yield('title')</h1>
            <p class="admin-error-page__message">@yield('message')</p>
            @hasSection('hint')
                <p class="admin-error-page__hint">@yield('hint')</p>
            @endif
            <div class="admin-error-page__actions">
                <a href="{{ url('/admin') }}" class="admin-error-page__btn admin-error-page__btn--primary">
                    بازگشت به داشبورد
                </a>
                <a href="javascript:history.back()" class="admin-error-page__btn admin-error-page__btn--ghost">
                    بازگشت به عقب
                </a>
            </div>
        </div>
    </div>
</body>
</html>

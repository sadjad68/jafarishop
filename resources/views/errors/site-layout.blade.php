<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title') | {{ $errorSiteName }}</title>
    @if ($errorFavicon)
        <link rel="icon" type="image/png" href="{{ $errorFavicon }}">
    @endif

    @if ($errorTheme === 'theme2')
        <style>
            @font-face {
                font-family: "yekan-extra-bold";
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/iranyekan/woff/iranyekanwebextraboldfanum.woff') }}');
                font-display: swap;
            }
            @font-face {
                font-family: "yekan-bold";
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/iranyekan/woff/iranyekanwebboldfanum.woff') }}');
                font-display: swap;
            }
            @font-face {
                font-family: "yekan-medium-FA";
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/iranyekan/woff/iranyekanwebmediumfanum.woff') }}');
                font-display: swap;
            }
            body { font-family: yekan-medium-FA, Tahoma, sans-serif; }
        </style>
    @else
        <style>
            @font-face {
                font-family: pelak-medium;
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/PelakFA-Medium.woff') }}');
                font-display: swap;
            }
            @font-face {
                font-family: pelak-bold;
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/PelakFA-Bold.woff') }}');
                font-display: swap;
            }
            body { font-family: pelak-medium, Tahoma, sans-serif; }
        </style>
    @endif

    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset($errorMainCss) }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-site-error.css?v1.0') }}">
    <style>
        :root {
            --color-one: {{ $themeColors['color-one'] }};
            --color-two: {{ $themeColors['color-two'] }};
            --color-body: {{ $themeColors['color-body'] }};
            --text-primery: {{ $themeColors['text-primary'] }};
            --text-secondary: {{ $themeColors['text-secondary'] }};
            --bg-table: {{ $themeColors['bg-table'] }};
        }
    </style>
</head>
<body class="error-page theme-{{ $errorTheme }}">
    <div class="error-page__ambient" aria-hidden="true">
        <span class="error-page__orb error-page__orb--1"></span>
        <span class="error-page__orb error-page__orb--2"></span>
    </div>

    <header class="error-page__header">
        <div class="container">
            <a href="{{ url('/') }}" class="error-page__logo" aria-label="بازگشت به {{ $errorSiteName }}">
                @if ($errorLogo)
                    <img src="{{ $errorLogo }}" alt="{{ $errorSiteName }}" width="120" height="48">
                @else
                    <span class="error-page__title mb-0">{{ $errorSiteName }}</span>
                @endif
            </a>
        </div>
    </header>

    <main class="error-page__main" role="main">
        <div class="container">
            <div class="error-page__card">
                <div class="error-page__code" aria-hidden="true">@yield('code')</div>
                <h1 class="error-page__title">@yield('title')</h1>
                <p class="error-page__message">@yield('message')</p>
                @hasSection('hint')
                    <p class="error-page__hint">@yield('hint')</p>
                @endif
                <div class="error-page__actions">
                    <a href="{{ url('/') }}" class="error-page__btn error-page__btn--primary">
                        بازگشت به صفحه اصلی
                    </a>
                    @if ($errorPhone)
                        <a href="tel:{{ $errorPhone }}" class="error-page__btn error-page__btn--ghost" dir="ltr">
                            {{ $errorPhone }}
                        </a>
                    @else
                        <a href="javascript:history.back()" class="error-page__btn error-page__btn--ghost">
                            بازگشت به عقب
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <footer class="error-page__footer">
        <div class="container">&copy; {{ $errorSiteName }}</div>
    </footer>
</body>
</html>

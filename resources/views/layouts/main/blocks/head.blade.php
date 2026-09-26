<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title_seo', @$default_seo['title_seo'])</title>
    <meta name="title" content="@yield('title_seo', @$default_seo['title_seo'])">
    <meta name="description" content="@yield('description_seo', @$default_seo['description_seo'])" />
    @php $site_config = \App\Library\SiteHelper::getInformation(); @endphp
    @if(!@$site_config['cms_temporary_domain'])
        @if ($default_seo['noindex'])
            <meta name="robots" content="noindex,nofollow">
            <meta name="googlebot" content="noindex,nofollow">
        @else
            <meta name="robots" content="@yield('robots', 'index,follow')">
            <meta name="googlebot" content="@yield('robots', 'index,follow')">
        @endif
    @else
        <meta name="robots" content="noindex,nofollow">
        <meta name="googlebot" content="noindex,nofollow">
    @endif
    <link rel="canonical" href="{{ $canonical }}" />
    <meta property="og:site_name" content="@yield('title', @$settings['siteName_fa'])" />
    <meta property="og:title" content="@yield('title_seo', @$default_seo['title_seo'])">
    <meta property="og:description" content="@yield('description_seo', @$default_seo['description_seo'])" />
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="@yield('image_seo', @$settings['logo'])" />
    <meta property="og:type" content="@yield('type', 'website')" />
    <meta property="twitter:domain" content="{{ \request()->getHost() }}">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title_seo', @$default_seo['title_seo'])">
    <meta name="twitter:description" content="@yield('description_seo', @$default_seo['description_seo'])">
    <meta name="twitter:image" content="@yield('image_seo', @$settings['logo'])">
    {{-- fonts-theme2 --}}
    @if ($theme_provider->getValue() == 'theme2')
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
             @font-face {
                font-family: "yekan-medium-EN";
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/iranyekan/woff/IRANYekanX-Medium.woff') }}');
                font-display: swap;
            }

            @font-face {
                font-family: "yekan-regular";
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/iranyekan/woff/iranyekanwebregularfanum.woff') }}');
                font-display: swap;
            }

            @font-face {
                font-family: "pelak-rgular";
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/Pelak-Regular.woff') }}');
                font-display: swap;
            }

            .font-md {
                font-family: yekan-medium-FA !important;
            }

            .font-bold {
                font-family: yekan-bold !important;
            }

            .font-e-bold {
                font-family: yekan-extra-bold !important;
            }

            .font-re {
                font-family: yekan-regular !important;
            }

            .font-re-2 {
                font-family: pelak-rgular !important;
            }

            body {
                font-family: yekan-medium-FA !important;
            }

             .f-number-en{
                font-family: yekan-medium-EN !important;
            }
        </style>
    @endif

    {{-- fonts-theme1: Estedad letters + Vazirmatn-FD digits, still pelak-* --}}
    @if ($theme_provider->getValue() == 'theme1')
        @php
            $t1Letters = 'U+0000-002F, U+003A-065F, U+066A-06EF, U+06FA-10FFFF';
            $t1Digits = 'U+0030-0039, U+0660-0669, U+06F0-06F9';
            $t1Faces = [
                'pelak-medium' => ['Estedad-Medium.woff2', 'Vazirmatn-FD-Medium.woff2'],
                'pelak-bold' => ['Estedad-Bold.woff2', 'Vazirmatn-FD-Bold.woff2'],
                'pelak-rgular' => ['Estedad-Regular.woff2', 'Vazirmatn-FD-Regular.woff2'],
                'pelak-thin' => ['Estedad-Light.woff2', 'Vazirmatn-FD-Light.woff2'],
                'pelak-num' => ['Estedad-SemiBold.woff2', 'Vazirmatn-FD-SemiBold.woff2'],
                'pelak-num-r' => ['Estedad-Regular.woff2', 'Vazirmatn-FD-Regular.woff2'],
            ];
        @endphp
        <link rel="preload" href="{{ asset('assets/site/fonts/theme1/Estedad-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{ asset('assets/site/fonts/theme1/Estedad-Medium.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{ asset('assets/site/fonts/theme1/Estedad-Bold.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{ asset('assets/site/fonts/theme1/Vazirmatn-FD-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
        <style>
            @foreach ($t1Faces as $t1Family => $t1Files)
            @font-face {
                font-family: {{ $t1Family }};
                font-style: normal;
                font-weight: 400;
                src: url('{{ asset('assets/site/fonts/theme1/'.$t1Files[0]) }}') format('woff2');
                unicode-range: {{ $t1Letters }};
                font-display: swap;
            }
            @font-face {
                font-family: {{ $t1Family }};
                font-style: normal;
                font-weight: 400;
                src: url('{{ asset('assets/site/fonts/theme1/'.$t1Files[1]) }}') format('woff2');
                unicode-range: {{ $t1Digits }};
                font-display: swap;
            }
            @endforeach

            @font-face {
                font-family: playfair;
                font-style: normal;
                src: url('{{ asset('assets/site/fonts/PlayfairDisplay-Black.woff2') }}') format('woff2'),
                    url('{{ asset('assets/site/fonts/PlayfairDisplay-Black.woff') }}') format('woff');
                font-display: swap;
            }

            body {
                font-family: pelak-rgular, Tahoma, sans-serif !important;
            }

            .font-md {
                font-family: pelak-medium, Tahoma, sans-serif !important;
            }

            .font-bold {
                font-family: pelak-bold, Tahoma, sans-serif !important;
            }

            .font-re {
                font-family: pelak-rgular, Tahoma, sans-serif !important;
            }

            .font-playfair {
                font-family: playfair !important;
            }

            .font-num {
                font-family: pelak-num, Tahoma, sans-serif !important;
            }

            .font-num-r {
                font-family: pelak-num-r, Tahoma, sans-serif !important;
            }
        </style>
    @endif

    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-swiper-bundle.min.css?v0.01') }}">
    @php
        $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
    @endphp
    <link rel="stylesheet" href="{{ asset($theme_provider->getMainCss()) }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-site-dev.css') }}">

    @stack('meta_tags')
    {{--    favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ @$settings['favicon'] }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ @$settings['favicon'] }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ @$settings['favicon'] }}">
    {{-- <link rel="manifest" href="{{ @$settings['favicon'] }}"> --}}
    {!! @$settings['head_codes'] !!}

    @if ((int) (@$settings['ecommerce_tracking_enabled'] ?? 0) === 1)
        <script>window.__ecommerceTracking__ = { enabled: true };</script>
        <script src="{{ asset('assets/site/js/shared/tpl-ecommerce-tracking.js') }}"></script>
    @endif

    @php
        $themeColors = json_decode($themes['color_type'], true);
    @endphp
    <style>
        :root {
            --color-one: {{ $themeColors['color-one'] }};
            --color-two: {{ $themeColors['color-two'] }};
            --color-body: {{ $themeColors['color-body'] }};
            --text-primery: {{ $themeColors['text-primary'] }};
            --text-secondary: {{ $themeColors['text-secondary'] }};
            --bg-table: {{ $themeColors['bg-table'] }};
            --icon-filter: {{ $themeColors['text-primary'] == '#fff' ? 'brightness(0) invert(1)' : 'brightness(0)' }};
        }

        [v-cloak] {
            display: none !important;
        }

        html.is-site-loading,
        html.is-site-loading body {
            overflow: hidden;
        }

        .site-page-loader {
            position: fixed;
            inset: 0;
            z-index: 100000;
            overflow: auto;
            background: var(--color-body, #f6f7f9);
            opacity: 1;
            visibility: visible;
            transition: opacity 220ms cubic-bezier(0.23, 1, 0.32, 1),
                visibility 220ms cubic-bezier(0.23, 1, 0.32, 1);
        }

        .site-page-loader.is-done {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .site-page-loader__frame {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .site-page-loader__header {
            display: flex;
            align-items: center;
            gap: 16px;
            min-height: 72px;
            padding: 12px clamp(16px, 4vw, 40px);
            background: #fff;
            border-bottom: 1px solid color-mix(in srgb, var(--color-one) 12%, rgba(20, 22, 26, 0.08));
        }

        .site-page-loader__nav,
        .site-page-loader__tools,
        .site-page-loader__thumbs,
        .site-page-loader__pills {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .site-page-loader__nav {
            flex: 1 1 auto;
            min-width: 0;
        }

        .site-page-loader__main {
            width: min(1180px, calc(100% - 32px));
            margin: 20px auto 32px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .site-page-loader__banner,
        .site-page-loader__stage,
        .site-page-loader__visual,
        .site-page-loader__counter {
            display: flex;
            flex-direction: column;
        }

        .site-page-loader__banner {
            gap: 12px;
            padding: 22px 24px;
            border-radius: 24px;
            background: linear-gradient(
                to left,
                #fff 38%,
                color-mix(in srgb, var(--color-one) 16%, #fff) 100%
            );
            border: 1px solid color-mix(in srgb, var(--color-one) 14%, rgba(20, 22, 26, 0.08));
        }

        .site-page-loader__stage {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(16rem, 0.92fr);
            gap: clamp(1rem, 3vw, 2.5rem);
            align-items: start;
        }

        .site-page-loader__visual,
        .site-page-loader__counter {
            gap: 12px;
        }

        .site-page-loader__bone {
            display: block;
            background: linear-gradient(
                90deg,
                #eceef2 0%,
                color-mix(in srgb, var(--color-one) 16%, #eceef2) 45%,
                #eceef2 90%
            );
            background-size: 200% 100%;
            animation: site-page-loader-shimmer 1.35s ease-in-out infinite;
        }

        .site-page-loader__logo {
            width: 112px;
            height: 36px;
            border-radius: 12px;
            flex-shrink: 0;
        }

        .site-page-loader__chip {
            width: 72px;
            height: 14px;
            border-radius: 999px;
        }

        .site-page-loader__search {
            width: min(220px, 28vw);
            height: 36px;
            border-radius: 999px;
        }

        .site-page-loader__icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .site-page-loader__line {
            height: 14px;
            width: 58%;
            border-radius: 999px;
        }

        .site-page-loader__line--title {
            width: min(72%, 420px);
            height: 28px;
        }

        .site-page-loader__line--crumb {
            width: min(48%, 280px);
            height: 12px;
        }

        .site-page-loader__line--wide {
            width: 86%;
            height: 18px;
        }

        .site-page-loader__media {
            width: 100%;
            aspect-ratio: 1;
            max-height: min(56vh, 520px);
            border-radius: 100px;
            border: 1px solid color-mix(in srgb, var(--color-one) 18%, transparent);
        }

        .site-page-loader__thumb {
            width: 56px;
            height: 56px;
            border-radius: 100px;
        }

        .site-page-loader__pill {
            width: 88px;
            height: 34px;
            border-radius: 999px;
        }

        .site-page-loader__panel {
            width: 100%;
            height: 168px;
            border-radius: 22px;
        }

        .site-page-loader__cta {
            width: 100%;
            height: 48px;
            border-radius: 999px;
        }

        .site-page-loader__line--short {
            width: 64%;
            height: 10px;
        }

        .site-page-loader__hero {
            width: 100%;
            height: min(42vh, 340px);
            border-radius: 28px;
        }

        .site-page-loader__cats,
        .site-page-loader__product-row,
        .site-page-loader__product-grid,
        .site-page-loader__card-grid,
        .site-page-loader__stat-row,
        .site-page-loader__steps {
            display: grid;
            gap: 12px;
        }

        .site-page-loader__cats {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }

        .site-page-loader__cat,
        .site-page-loader__product,
        .site-page-loader__tile,
        .site-page-loader__cart-copy,
        .site-page-loader__prose,
        .site-page-loader__aside,
        .site-page-loader__filter,
        .site-page-loader__plp-main,
        .site-page-loader__cart-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .site-page-loader__cat {
            align-items: center;
        }

        .site-page-loader__cat-media {
            width: 72px;
            height: 72px;
            border-radius: 50%;
        }

        .site-page-loader__section-head {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }

        .site-page-loader__product-row,
        .site-page-loader__product-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .site-page-loader__product-media,
        .site-page-loader__tile-media {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 18px;
        }

        .site-page-loader__strip {
            width: 100%;
            height: 120px;
            border-radius: 24px;
        }

        .site-page-loader__plp,
        .site-page-loader__article,
        .site-page-loader__cart,
        .site-page-loader__panel-grid {
            display: grid;
            gap: 18px;
            align-items: start;
        }

        .site-page-loader__plp,
        .site-page-loader__panel-grid {
            grid-template-columns: minmax(14rem, 0.32fr) minmax(0, 1fr);
        }

        .site-page-loader__article,
        .site-page-loader__cart {
            grid-template-columns: minmax(0, 1fr) minmax(16rem, 0.42fr);
        }

        .site-page-loader__card-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .site-page-loader__toolbar {
            width: 100%;
            height: 52px;
            border-radius: 16px;
        }

        .site-page-loader__panel--short {
            height: 92px;
        }

        .site-page-loader__panel--tall {
            height: 240px;
        }

        .site-page-loader__cover {
            width: 100%;
            height: 220px;
            border-radius: 22px;
        }

        .site-page-loader__steps {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .site-page-loader__step {
            height: 44px;
            border-radius: 999px;
        }

        .site-page-loader__cart-row {
            display: flex;
            gap: 12px;
            padding: 12px;
            border-radius: 18px;
            background: #fff;
            border: 1px solid color-mix(in srgb, var(--color-one) 10%, rgba(20, 22, 26, 0.08));
        }

        .site-page-loader__cart-thumb {
            width: 84px;
            height: 84px;
            border-radius: 16px;
            flex-shrink: 0;
        }

        .site-page-loader__cart-copy {
            flex: 1 1 auto;
            justify-content: center;
        }

        .site-page-loader__auth {
            display: flex;
            justify-content: center;
            padding: 32px 0 16px;
        }

        .site-page-loader__auth-card {
            width: min(420px, 100%);
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 28px 24px;
            border-radius: 24px;
            background: #fff;
            border: 1px solid color-mix(in srgb, var(--color-one) 14%, rgba(20, 22, 26, 0.08));
        }

        .site-page-loader__field {
            width: 100%;
            height: 44px;
            border-radius: 14px;
        }

        .site-page-loader__stat-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .site-page-loader__stat {
            height: 88px;
            border-radius: 18px;
        }

        @keyframes site-page-loader-shimmer {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        @media (max-width: 991.98px) {
            .site-page-loader__nav {
                display: none;
            }

            .site-page-loader__stage,
            .site-page-loader__plp,
            .site-page-loader__article,
            .site-page-loader__cart,
            .site-page-loader__panel-grid {
                grid-template-columns: 1fr;
            }

            .site-page-loader__media {
                border-radius: 24px;
                max-height: 58vw;
            }

            .site-page-loader__cats {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .site-page-loader__product-row,
            .site-page-loader__product-grid,
            .site-page-loader__card-grid,
            .site-page-loader__stat-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .site-page-loader__hero {
                height: 200px;
                border-radius: 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .site-page-loader,
            .site-page-loader.is-done {
                transition: none;
            }

            .site-page-loader__bone {
                animation: none;
            }
        }

        @media print {
            .site-page-loader {
                display: none !important;
            }
        }
    </style>

    <style>
        .btn-fix {
            position: fixed;
            bottom: calc(1.25rem + env(safe-area-inset-bottom, 0px));
            right: 1.25rem;
            z-index: 1040;
            display: flex;
            justify-content: flex-end;
            pointer-events: none;
        }

        .site-fab {
            pointer-events: auto;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 52px;
            width: 52px;
            max-width: 52px;
            padding-block: 4px;
            padding-inline: 4px;
            overflow: hidden;
            text-decoration: none;
            color: #fff;
            background: var(--sk-ink-900, #14161a);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            box-shadow: 0 14px 36px rgba(20, 22, 26, 0.22);
            transition: max-width 280ms cubic-bezier(0.23, 1, 0.32, 1),
                padding-inline 280ms cubic-bezier(0.23, 1, 0.32, 1),
                transform 180ms cubic-bezier(0.23, 1, 0.32, 1),
                box-shadow 180ms cubic-bezier(0.23, 1, 0.32, 1),
                background-color 160ms ease;
        }

        .site-fab__icon {
            flex: 0 0 44px;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
        }

        .site-fab__icon img {
            display: block;
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.18));
        }

        .site-fab__label {
            font-family: inherit;
            font-size: 13px;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 0;
            opacity: 0;
            transition: max-width 280ms cubic-bezier(0.23, 1, 0.32, 1),
                opacity 200ms ease;
        }

        .site-fab:visited {
            color: #fff;
        }

        .site-fab:hover,
        .site-fab:focus-visible,
        .site-fab.is-expanded {
            max-width: min(calc(100vw - 2rem), 320px);
            width: auto;
            padding-inline-end: 16px;
        }

        .site-fab:hover .site-fab__label,
        .site-fab:focus-visible .site-fab__label,
        .site-fab.is-expanded .site-fab__label {
            max-width: 240px;
            opacity: 1;
        }

        .site-fab:hover {
            color: #fff;
            text-decoration: none;
            background: color-mix(in srgb, var(--sk-ink-900, #14161a) 88%, var(--color-one));
            box-shadow: 0 18px 40px rgba(20, 22, 26, 0.28);
        }

        .site-fab:focus-visible {
            outline: 2px solid var(--color-one);
            outline-offset: 3px;
        }

        .site-fab:active {
            transform: scale(0.97);
        }

        @media (hover: hover) and (pointer: fine) {
            .site-fab:hover {
                transform: translateY(-3px);
            }
        }

        @media (max-width: 576px) {
            body:not(:has(.product-page)) .btn-fix {
                bottom: calc(5.25rem + env(safe-area-inset-bottom, 0px));
                right: 0.75rem;
            }

            .site-fab {
                min-height: 48px;
                width: 48px;
                max-width: 48px;
            }

            .site-fab:hover,
            .site-fab:focus-visible,
            .site-fab.is-expanded {
                max-width: min(calc(100vw - 1.5rem), 300px);
                padding-inline-end: 14px;
            }

            .site-fab__icon,
            .site-fab__icon img {
                width: 40px;
                height: 40px;
            }

            .site-fab__icon {
                flex-basis: 40px;
            }

            .site-fab__label {
                font-size: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .site-fab,
            .site-fab:hover,
            .site-fab:active {
                transition: none;
                transform: none;
            }
        }
    </style>
    <style>
        /* تغییر فونت متن داخل tooltip */
        .tooltip-inner {
            font-family: inherit !important;
            font-size: 12px;
            color: #fff;
        }
    </style>
    @stack('styles')
    @if ($theme_provider->getValue() == 'theme2')
        <link rel="stylesheet" href="{{ asset('assets/site/css/shop/tpl-theme2-inner.min.css?v1.06') }}">
    @endif
    <script src="{{ asset('assets/site/js/tpl-vue.min.js?v0.01') }}"></script>
    <script src="{{ asset('assets/site/js/tpl-axios.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/tpl-sweetalert.min.js') }}"></script>
</head>

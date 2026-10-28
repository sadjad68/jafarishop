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
    </style>
    <link rel="stylesheet" href="{{ asset('assets/site/css/shared/tpl-site-head.css') }}">

    @stack('styles')
    @if ($theme_provider->getValue() == 'theme2')
        <link rel="stylesheet" href="{{ asset('assets/site/css/shop/tpl-theme2-inner.min.css?v1.06') }}">
    @endif
    <script src="{{ asset('assets/site/js/tpl-vue.min.js?v0.01') }}"></script>
    <script src="{{ asset('assets/site/js/tpl-axios.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/tpl-sweetalert.min.js') }}"></script>
</head>

@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : ($settings['about_us_page_title'] ?? 'درباره ما');
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'about-page-title',
    'bannerEyebrow' => 'آشنایی',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => $settings['about_us_page_title'] ?? 'درباره ما'],
    ],
    'bannerStatLogo' => $settings['logo'] ?? null,
    'bannerStatLogoAlt' => $settings['siteName_fa'] ?? '',
    'bannerStatIcon' => empty($settings['logo']) ? 'bi-building' : null,
    'bannerStatLabel' => $settings['siteName_fa'] ?? 'مجموعه',
])

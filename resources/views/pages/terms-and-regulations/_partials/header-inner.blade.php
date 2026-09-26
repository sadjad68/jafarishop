@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : ($settings['terms_and_conditions_page_title'] ?? 'قوانین و مقررات');
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'terms-page-title',
    'bannerEyebrow' => 'قوانین',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => $settings['terms_and_conditions_page_title'] ?? 'قوانین و مقررات'],
    ],
    'bannerStatIcon' => 'bi-shield-check',
    'bannerStatLabel' => 'شرایط استفاده',
])

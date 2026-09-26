@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : ($settings['contact_us_page_title'] ?? 'تماس با ما');
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'contact-page-title',
    'bannerEyebrow' => 'ارتباط',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => $settings['contact_us_page_title'] ?? 'تماس با ما'],
    ],
    'bannerStatIcon' => 'bi-telephone-fill',
    'bannerStatLabel' => 'پاسخ‌گو هستیم',
])

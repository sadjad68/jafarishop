@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'نمونه کارها';
    $sampleCount = isset($samples) ? count($samples) : 0;
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'samples-page-title',
    'bannerEyebrow' => 'گالری',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'نمونه کارها'],
    ],
    'bannerStatIcon' => 'bi-images',
    'bannerStatNum' => $sampleCount,
    'bannerStatLabel' => 'نمونه',
])

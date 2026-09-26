@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'پکیج ها';
    $packageCount = isset($packages) ? count($packages) : 0;
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'packages-page-title',
    'bannerEyebrow' => 'پکیج‌های ما',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'پکیج ها'],
    ],
    'bannerStatIcon' => 'bi-box-seam',
    'bannerStatNum' => $packageCount,
    'bannerStatLabel' => 'پکیج',
])

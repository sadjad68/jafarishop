@php
    $pageTitle = $package->seo_h1 ?: $package['title'];
    $serviceCount = $package->services->count();
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'package-detail-title',
    'bannerEyebrow' => 'پکیج‌های ما',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'پکیج ها', 'url' => route('package.list')],
        ['label' => $package['title']],
    ],
    'bannerStatIcon' => 'bi-box-seam',
    'bannerStatNum' => $serviceCount,
    'bannerStatLabel' => 'خدمت',
])

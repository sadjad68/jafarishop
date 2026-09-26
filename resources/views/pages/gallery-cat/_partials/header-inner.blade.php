@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'گالری';
    $albumCount = isset($gallery_categories) ? count($gallery_categories) : 0;
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'gallery-page-title',
    'bannerEyebrow' => 'آلبوم تصاویر',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'گالری'],
    ],
    'bannerStatIcon' => 'bi-images',
    'bannerStatNum' => $albumCount,
    'bannerStatLabel' => 'آلبوم',
])

@php
    $pageTitle = $gallery_category->seoH1 ?: $gallery_category->title;
    $photoCount = isset($galleries) ? count($galleries) : 0;
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'gallery-album-title',
    'bannerEyebrow' => 'گالری',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'گالری', 'url' => route('gallery.category')],
        ['label' => $gallery_category['title']],
    ],
    'bannerStatIcon' => 'bi-camera',
    'bannerStatNum' => $photoCount,
    'bannerStatLabel' => 'تصویر',
])

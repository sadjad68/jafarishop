@php
    $blog_categories = $blog_categories ?? collect();
    $categoryCount = count($blog_categories);
    $isNested = !empty($blog_category);
    $pageTitle = $isNested
        ? @$blog_category->getH1PagesAttribute($blog_category)
        : (@$seo_data->h1 ? $seo_data->h1 : 'مطالب');
    $bannerCrumbs = [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'مطالب', 'url' => $isNested ? route('blog.category-list') : null],
    ];
    if ($isNested && @$blog_category->parent) {
        $bannerCrumbs[] = [
            'label' => $blog_category->parent->title,
            'url' => route('blog.list', ['url' => $blog_category->parent->url]),
        ];
    }
    if ($isNested) {
        $bannerCrumbs[] = ['label' => $blog_category['title']];
    }
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'blog-topics-title',
    'bannerEyebrow' => 'خواندنی‌ها',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => $bannerCrumbs,
    'bannerStatLogo' => $isNested ? $blog_category->item_image : null,
    'bannerStatLogoAlt' => $isNested ? $blog_category['title'] : '',
    'bannerStatIcon' => $isNested ? null : 'bi-journal-richtext',
    'bannerStatNum' => $categoryCount,
    'bannerStatLabel' => 'موضوع',
])

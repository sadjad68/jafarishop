@php
    $blogs = $blogs ?? collect();
    $blogCount = count($blogs);
    $pageTitle = @$blog_category->getH1PagesAttribute($blog_category);
    $bannerCrumbs = [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'مطالب', 'url' => route('blog.category-list')],
    ];
    if (@$blog_category->parent) {
        $bannerCrumbs[] = [
            'label' => $blog_category->parent->title,
            'url' => route('blog.list', ['url' => $blog_category->parent->url]),
        ];
    }
    $bannerCrumbs[] = ['label' => $blog_category['title']];
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'blog-list-title',
    'bannerEyebrow' => 'خواندنی‌ها',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => $bannerCrumbs,
    'bannerStatLogo' => $blog_category->item_image,
    'bannerStatLogoAlt' => $blog_category['title'],
    'bannerStatNum' => $blogCount,
    'bannerStatLabel' => 'مطلب',
])

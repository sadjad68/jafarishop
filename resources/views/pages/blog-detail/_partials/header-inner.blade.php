@php
    $pageTitle = @$blog->getH1PagesAttribute($blog);
    $readingTime = \App\Library\SiteHelper::getReadingTime($blog['description'] ?? '');
    $category = $blog->category ?? null;
    $bannerCrumbs = [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'مطالب', 'url' => route('blog.category-list')],
    ];
    if ($category && $category->parent) {
        $bannerCrumbs[] = [
            'label' => $category->parent->title,
            'url' => route('blog.list', ['url' => $category->parent->url]),
        ];
    }
    if ($category) {
        $bannerCrumbs[] = [
            'label' => $category->title,
            'url' => route('blog.list', ['url' => $category->url]),
        ];
    }
    $bannerCrumbs[] = ['label' => $blog['title']];
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'blog-article-title',
    'bannerEyebrow' => $category ? $category->title : 'خواندنی‌ها',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => $bannerCrumbs,
    'bannerStatLogo' => $blog->getItemImage(),
    'bannerStatLogoAlt' => $blog['title'],
    'bannerStatNum' => $readingTime,
    'bannerStatLabel' => 'دقیقه مطالعه',
])

<div class="blog-article__intro">
    <div class="container">
        <ul class="blog-article__meta">
            @if(!empty($blog['author']))
                <li>
                    <i class="bi bi-person" aria-hidden="true"></i>
                    {{ $blog['author'] }}
                </li>
            @endif
            <li>
                <i class="bi bi-calendar4" aria-hidden="true"></i>
                {{ jdate('l j F Y', $blog['publish_date']) }}
            </li>
            <li>
                <i class="bi bi-eye" aria-hidden="true"></i>
                {{ $blog['view'] }} بازدید
            </li>
            <li>
                <button type="button"
                        class="blog-article__share"
                        data-bs-toggle="modal"
                        data-bs-target="#shareBlog"
                        aria-haspopup="dialog">
                    <i class="bi bi-share" aria-hidden="true"></i>
                    اشتراک‌گذاری
                </button>
            </li>
        </ul>

        <figure class="blog-article__cover">
            <img src="{{ $blog->getItemImage() }}"
                 alt="{{ $blog['title'] }}"
                 title="{{ $blog['title'] }}"
                 width="960"
                 height="540">
            @if($category)
                <figcaption>
                    <a href="{{ route('blog.list', ['url' => $category->url]) }}">{{ $category->title }}</a>
                </figcaption>
            @endif
        </figure>
    </div>
</div>
@include('pages.blog-detail._partials.share-modal')

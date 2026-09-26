@extends('layouts.main.master')
@section('robots', @$blog_category->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$blog_category->seoTitle ? $blog_category->seoTitle : $blog_category->title)
@section('description_seo',@$blog_category->seoDescription)
@section('image_seo',@$blog_category->item_image)
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $blogs = $blogs ?? collect();
        $blog_categories = $blog_categories ?? collect();
        $blogCount = count($blogs);
        $subcatCount = count($blog_categories);
        $showSearch = $blogCount > 4;
        $useFeatured = $blogCount >= 3;
    @endphp

    @include('pages.blog-list._partials.header-inner')

    <section class="list-blogs" aria-labelledby="blog-list-title">
        <div class="container">
            @if($subcatCount > 0)
                <nav class="list-blogs__cats" aria-label="زیردسته‌های این موضوع">
                    @foreach($blog_categories as $row)
                        <a href="{{ route('blog.list', ['url' => $row['url']]) }}" class="list-blogs__cat">
                            <img src="{{ $row['item_image'] }}"
                                 alt=""
                                 width="32"
                                 height="32">
                            <span>{{ $row['title'] }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            @if($showSearch)
                <div class="list-blogs__search" role="search">
                    <label class="visually-hidden" for="blog-list-search">جستجوی عنوان مطلب</label>
                    <input id="blog-list-search"
                           type="search"
                           class="form-control"
                           placeholder="جستجوی عنوان مطلب"
                           autocomplete="off"
                           data-blog-search>
                    <span class="list-blogs__search-icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            @endif

            @if($blogCount > 0)
                <div class="list-blogs__grid{{ $useFeatured ? ' list-blogs__grid--featured' : '' }}"
                     id="blog-list-grid">
                    @foreach($blogs as $blog)
                        <article class="list-blogs__item{{ $useFeatured && $loop->first ? ' list-blogs__item--lead' : '' }}"
                                 data-blog-item
                                 data-title="{{ mb_strtolower($blog['title'] ?? '') }}">
                            @include('layouts.common.blog.blog-card', [
                                'blog' => $blog,
                                'featured' => $useFeatured && $loop->first,
                                'kicker' => $useFeatured && $loop->first ? ($blog_category['title'] ?? null) : null,
                            ])
                        </article>
                    @endforeach
                </div>
                <p class="list-blogs__empty" id="blog-list-empty" hidden aria-live="polite">
                    مطلبی با این عنوان پیدا نشد.
                </p>
            @elseif($subcatCount > 0)
                <p class="list-blogs__empty list-blogs__empty--page">
                    در این موضوع هنوز مطلبی منتشر نشده. یکی از زیردسته‌ها را انتخاب کنید.
                </p>
            @else
                <p class="list-blogs__empty list-blogs__empty--page">
                    هنوز مطلبی برای نمایش وجود ندارد.
                </p>
            @endif

            @if(@$blog_category['description'])
                <section class="list-blogs__seo" aria-label="درباره این موضوع">
                    <p class="list-blogs__seo-eyebrow">درباره این موضوع</p>
                    <div class="list-blogs__seo-body content">
                        {!! $blog_category['description'] !!}
                    </div>
                </section>
            @endif
        </div>
    </section>
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">
@endpush
@push('scripts')
    <script>
        (function () {
            var input = document.querySelector('[data-blog-search]');
            var items = document.querySelectorAll('[data-blog-item]');
            var empty = document.getElementById('blog-list-empty');
            var grid = document.getElementById('blog-list-grid');
            if (!input || !items.length) {
                return;
            }
            function filterBlogs() {
                var query = (input.value || '').trim().toLowerCase();
                var visible = 0;
                items.forEach(function (item) {
                    var haystack = item.getAttribute('data-title') || '';
                    var show = !query || haystack.indexOf(query) !== -1;
                    item.hidden = !show;
                    if (show) {
                        visible += 1;
                    }
                });
                if (grid) {
                    grid.hidden = visible === 0;
                }
                if (empty) {
                    empty.hidden = visible !== 0;
                }
            }
            input.addEventListener('input', filterBlogs);
        })();
    </script>
@endpush
@push('schema')
    <script type="application/ld+json">
        {
          "@@context": "https://schema.org/",
          "@@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@@type": "ListItem",
              "position": 1,
              "name": "{{$settings['siteName_fa']}}",
              "item": "{{route('index')}}"
        },

            {
              "@@type": "ListItem",
              "position": 2,
              "name": "مطالب",
              "item": "{{route('blog.category-list')}}"
        },
        @if(@$blog_category->parent)
            {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{@$blog_category->parent->title}}",
          "item": "{{ route('blog.list', ['url' => @$blog_category->parent->url]) }}"
        },
        @endif
          {
          "@@type": "ListItem",
               @if(@$blog_category->parent)
          "position": 4,
          @else
            "position": 3,
@endif
          "name": "{{$blog_category['title']}}",
          "item": "{{ route('blog.list', ['url' => $blog_category['url']]) }}"
        }
        ],
"name": "menu"
}
</script>
@endpush

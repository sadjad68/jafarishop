@extends('layouts.main.master')
@if(@$blog_category)
    @section('robots', @$blog_category->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
    @section('title_seo',@$blog_category->seoTitle ? $blog_category->seoTitle : $blog_category->title)
    @section('description_seo',@$blog_category->seoDescription)
    @section('image_seo',@$blog_category->item_image)
@else
    @section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
    @section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : 'مطالب')
    @section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : 'لیست مطالب')
@endif
@section('logo')
    <img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $blog_categories = $blog_categories ?? collect();
        $blogs = $blogs ?? collect();
        $categoryCount = count($blog_categories);
        $blogCount = count($blogs);
        $showSearch = $categoryCount >= 6;
        $useFeatured = $categoryCount >= 4;
    @endphp

    @include('pages.blog-category._partials.header-inner')

    <section class="blog-topics" aria-labelledby="blog-topics-title">
        <div class="container">
            @if($showSearch)
                <div class="blog-topics__search" role="search">
                    <label class="visually-hidden" for="blog-topics-search">جستجوی نام موضوع</label>
                    <input id="blog-topics-search"
                           type="search"
                           class="form-control"
                           placeholder="جستجوی نام موضوع"
                           autocomplete="off"
                           data-topic-search>
                    <span class="blog-topics__search-icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            @endif

            @if($categoryCount > 0)
                <ul class="blog-topics__grid{{ $useFeatured ? ' blog-topics__grid--featured' : '' }}"
                    id="blog-topics-grid">
                    @foreach($blog_categories as $row)
                        @php
                            $children = $row->children ?? collect();
                            $childCount = (int) ($row->children_count ?? $children->count());
                            $postCount = (int) ($row->blogs_count ?? 0);
                            $visibleChildren = $children->take(3);
                            $hiddenChildCount = max(0, $childCount - $visibleChildren->count());
                            $childTitles = $children->pluck('title')->filter()->implode(' ');
                            $isFeatured = $useFeatured && $loop->first;
                        @endphp
                        <li class="blog-topics__item{{ $isFeatured ? ' blog-topics__item--lead' : '' }}"
                            data-topic-item
                            data-title="{{ mb_strtolower($row['title'] ?? '') }}"
                            data-children="{{ mb_strtolower($childTitles) }}">
                            <article class="blog-topics__card{{ $isFeatured ? ' blog-topics__card--lead' : '' }}">
                                <a href="{{ route('blog.list', ['url' => $row['url']]) }}"
                                   class="blog-topics__cover">
                                    <span class="blog-topics__media">
                                        <img src="{{ $row['item_image'] }}"
                                             width="640"
                                             height="400"
                                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                             alt="{{ $row['title'] }}"
                                             title="{{ $row['title'] }}">
                                    </span>
                                    <span class="blog-topics__copy">
                                        <span class="blog-topics__name">{{ $row['title'] }}</span>
                                        <span class="blog-topics__meta">
                                            @if($postCount > 0)
                                                {{ $postCount }} مطلب
                                            @endif
                                            @if($postCount > 0 && $childCount > 0)
                                                <span aria-hidden="true">·</span>
                                            @endif
                                            @if($childCount > 0)
                                                {{ $childCount }} زیردسته
                                            @endif
                                            @if($postCount === 0 && $childCount === 0)
                                                ورود به موضوع
                                            @endif
                                        </span>
                                        <span class="blog-topics__cta">
                                            مشاهده مطالب
                                            <i class="bi bi-arrow-left" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                </a>
                                @if($childCount > 0)
                                    <ul class="blog-topics__subs">
                                        @foreach($visibleChildren as $child)
                                            <li>
                                                <a href="{{ route('blog.list', ['url' => $child['url']]) }}"
                                                   class="blog-topics__chip">
                                                    {{ $child['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                        @if($hiddenChildCount > 0)
                                            <li>
                                                <a href="{{ route('blog.list', ['url' => $row['url']]) }}"
                                                   class="blog-topics__chip blog-topics__chip--more">
                                                    +{{ $hiddenChildCount }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </article>
                        </li>
                    @endforeach
                </ul>
                <p class="blog-topics__empty" id="blog-topics-empty" hidden aria-live="polite">
                    موضوعی با این نام پیدا نشد.
                </p>
            @else
                <p class="blog-topics__empty blog-topics__empty--page">
                    هنوز موضوعی برای نمایش وجود ندارد.
                </p>
            @endif

            @if($blogCount > 0)
                <div class="list-blogs__grid blog-topics__posts">
                    @foreach($blogs as $blog)
                        <article class="list-blogs__item">
                            @include('layouts.common.blog.blog-card', ['blog' => $blog])
                        </article>
                    @endforeach
                </div>
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
            var input = document.querySelector('[data-topic-search]');
            var items = document.querySelectorAll('[data-topic-item]');
            var empty = document.getElementById('blog-topics-empty');
            var grid = document.getElementById('blog-topics-grid');
            if (!input || !items.length) {
                return;
            }
            input.addEventListener('input', function () {
                var query = (this.value || '').trim().toLowerCase();
                var visible = 0;
                items.forEach(function (item) {
                    var haystack = (item.getAttribute('data-title') || '') + ' ' + (item.getAttribute('data-children') || '');
                    var show = !query || haystack.indexOf(query) !== -1;
                    item.hidden = !show;
                    if (show) {
                        visible += 1;
                    }
                });
                if (grid) {
                    grid.classList.toggle('is-filtering', query.length > 0);
                    grid.hidden = visible === 0;
                }
                if (empty) {
                    empty.hidden = visible !== 0;
                }
            });
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
              "name": "{{ $settings['siteName_fa'] }}",
      "item": "{{ route('index') }}"
    },
    {
      "@@type": "ListItem",
      "position": 2,
      "name": "مطالب",
      "item": "{{ route('blog.category-list') }}"
    }
        @if(@$blog_category)
            @if(@$blog_category->parent)
                ,{
                  "@@type": "ListItem",
                  "position": 3,
                  "name": "{{ @$blog_category->parent->title }}",
          "item": "{{ route('blog.list', ['url' => @$blog_category->parent->url]) }}"
        },
        {
          "@@type": "ListItem",
          "position": 4,
          "name": "{{ $blog_category['title'] }}",
          "item": "{{ route('blog.list', ['url' => $blog_category['url']]) }}"
        }
            @else
                ,{
                  "@@type": "ListItem",
                  "position": 3,
                  "name": "{{ $blog_category['title'] }}",
          "item": "{{ route('blog.list', ['url' => $blog_category['url']]) }}"
        }
            @endif
        @endif
        ]
      }
    </script>

@endpush

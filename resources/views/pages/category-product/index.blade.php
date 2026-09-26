@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "دسته")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "لیست دسته")
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $categoryCount = count($product_categories);
        $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'دسته‌بندی محصولات';
        $showSearch = $categoryCount >= 6;
        $useFeatured = $categoryCount >= 5;
    @endphp
    <section class="hero position-relative hero-auth sk-page-hero cat-index-hero" aria-labelledby="categories-page-title">
        <div class="container">
            <div class="sk-page-banner">
                <div class="sk-page-banner__copy">
                    <span class="sk-page-banner__eyebrow">مسیر خرید</span>
                    <h1 id="categories-page-title" class="sk-page-banner__title">
                        {{ $pageTitle }}
                    </h1>
                    <nav class="sk-page-banner__crumb" aria-label="breadcrumb">
                        <a href="{{ route('index') }}">
                            <i class="bi bi-house" aria-hidden="true"></i>
                            خانه
                        </a>
                        <span class="sk-page-banner__sep" aria-hidden="true">/</span>
                        <span aria-current="page">دسته‌بندی محصولات</span>
                    </nav>
                </div>
                <div class="sk-page-banner__stat">
                    <i class="bi bi-grid-fill sk-page-banner__stat-icon" aria-hidden="true"></i>
                    <strong class="sk-page-banner__stat-num">{{ $categoryCount }}</strong>
                    <span class="sk-page-banner__stat-label">دسته فعال</span>
                </div>
            </div>
        </div>
    </section>

    <section class="cat-index" aria-labelledby="categories-page-title">
        <div class="container">
            @if($showSearch)
                <div class="cat-index__search" role="search">
                    <label class="visually-hidden" for="cat-index-search">جستجوی نام دسته</label>
                    <input id="cat-index-search"
                           type="search"
                           class="form-control"
                           placeholder="جستجوی نام دسته"
                           autocomplete="off"
                           data-cat-search>
                    <span class="cat-index__search-icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            @endif

            @if($categoryCount > 0)
                <ul class="cat-index__grid{{ $useFeatured ? ' cat-index__grid--featured' : '' }}"
                    id="cat-index-grid"
                    data-reveal-group>
                    @foreach($product_categories as $product_category)
                        @php
                            $children = $product_category->children ?? collect();
                            $childCount = $children->count();
                            $childTitles = $children->pluck('title')->filter()->implode(' ');
                            $isFeatured = $useFeatured && $loop->first;
                            $visibleChildren = $isFeatured ? $children->take(5) : $children->take(3);
                            $hiddenChildCount = max(0, $childCount - $visibleChildren->count());
                        @endphp
                        <li class="cat-index__item"
                            data-reveal="zoom"
                            data-cat-item
                            data-title="{{ mb_strtolower($product_category['title'] ?? '') }}"
                            data-children="{{ mb_strtolower($childTitles) }}">
                            <article class="cat-index__card{{ $isFeatured ? ' cat-index__card--featured' : '' }}">
                                <a href="{{ \App\Library\SiteUrl::category($product_category) }}"
                                   class="cat-index__main">
                                    <span class="cat-index__media">
                                        <img src="{{ $product_category->getImage('big') }}"
                                             width="300"
                                             height="300"
                                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                             alt="{{ @$product_category['title'] }}"
                                             title="{{ @$product_category['title'] }}">
                                    </span>
                                    <span class="cat-index__body">
                                        <span class="cat-index__name">{{ @$product_category['title'] }}</span>
                                        @if($childCount > 0)
                                            <span class="cat-index__meta">{{ $childCount }} زیردسته</span>
                                        @endif
                                    </span>
                                </a>
                                @if($childCount > 0)
                                    <ul class="cat-index__subs">
                                        @foreach($visibleChildren as $child)
                                            <li>
                                                <a href="{{ \App\Library\SiteUrl::category($child) }}"
                                                   class="cat-index__chip">
                                                    {{ $child['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                        @if($hiddenChildCount > 0)
                                            <li>
                                                <a href="{{ \App\Library\SiteUrl::category($product_category) }}"
                                                   class="cat-index__chip cat-index__chip--more">
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
                <p class="cat-index__empty" id="cat-index-empty" hidden aria-live="polite">
                    دسته‌ای با این نام پیدا نشد.
                </p>
            @else
                <p class="cat-index__empty cat-index__empty--page">
                    هنوز دسته‌ای برای نمایش وجود ندارد.
                </p>
            @endif
        </div>
    </section>

    @if(!empty($settings['category_description']))
        <section class="seo-box cat-index-seo">
            <div class="container">
                <div class="box">
                    <div class="boxdes">
                        <input type="checkbox" id="expanded">
                        <div id="text-box" class="p text-start content">
                            {!! @$settings['category_description'] !!}
                        </div>
                        @if ($theme_provider->getValue() != 'theme2')
                            <label for="expanded" id="more-button" role="button" class="btn button btn-one m-auto px-4 py-2">
                                بیشتر
                            </label>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/product/tpl-product-category.css?v0.22') }}">
@endpush
@push('scripts')
    <script>
        (function () {
            var input = document.querySelector('[data-cat-search]');
            var items = document.querySelectorAll('[data-cat-item]');
            var empty = document.getElementById('cat-index-empty');
            var grid = document.getElementById('cat-index-grid');
            if (!input || !items.length) {
                return;
            }
            input.addEventListener('input', function () {
                var query = (this.value || '').trim().toLowerCase();
                var visible = 0;
                items.forEach(function (item) {
                    var haystack = ((item.getAttribute('data-title') || '') + ' ' + (item.getAttribute('data-children') || ''));
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
        document.querySelectorAll('.seo-box table').forEach(function (item) {
            item.className = 'table table-bordered bg-transparent mx-auto table-striped';
            item.outerHTML = '<div class="table-responsive">' + item.outerHTML + '</div>';
        });
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
          "name": "دسته بندی محصولات",
          "item": "{{ route('category.list') }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

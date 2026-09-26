@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "برند ما")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "برندهای ما")
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $brandCount = count($brands);
        $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'برندها';
        $showSearch = $brandCount > 0;
    @endphp
    <section class="hero position-relative hero-auth sk-page-hero list-brands-hero" aria-labelledby="brands-page-title">
        <div class="container">
            <div class="sk-page-banner">
                <div class="sk-page-banner__copy">
                    <span class="sk-page-banner__eyebrow">انتخاب برند</span>
                    <h1 id="brands-page-title" class="sk-page-banner__title">
                        {{ $pageTitle }}
                    </h1>
                    <nav class="sk-page-banner__crumb" aria-label="breadcrumb">
                        <a href="{{ route('index') }}">
                            <i class="bi bi-house" aria-hidden="true"></i>
                            خانه
                        </a>
                        <span class="sk-page-banner__sep" aria-hidden="true">/</span>
                        <span aria-current="page">برندها</span>
                    </nav>
                </div>
                <div class="sk-page-banner__stat">
                    <i class="bi bi-award-fill sk-page-banner__stat-icon" aria-hidden="true"></i>
                    <strong class="sk-page-banner__stat-num">{{ $brandCount }}</strong>
                    <span class="sk-page-banner__stat-label">برند فعال</span>
                </div>
            </div>
        </div>
    </section>

    <section class="list-brands mx-md-0" aria-labelledby="brands-page-title">
        <div class="container">
            @if($showSearch)
                <div class="list-brands__search" role="search">
                    <label class="visually-hidden" for="brand-search">جستجوی نام برند</label>
                    <input id="brand-search"
                           type="search"
                           class="form-control"
                           placeholder="جستجوی نام برند"
                           autocomplete="off"
                           value="{{ request()->get('title') }}"
                           data-brand-search>
                    <span class="list-brands__search-icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            @endif

            @if($brandCount > 0)
                <div class="list-brands__grid" id="brand-list-grid">
                    @foreach($brands as $brand)
                        <div class="list-brands__item"
                             data-brand-item
                             data-title="{{ mb_strtolower($brand['title'] ?? '') }}">
                            @include('layouts.common.brand.brand-tile')
                        </div>
                    @endforeach
                </div>
                <p class="list-brands__empty" id="brand-list-empty" hidden aria-live="polite">
                    برندی با این نام پیدا نشد.
                </p>
            @else
                <p class="list-brands__empty list-brands__empty--page">
                    هنوز برندی برای نمایش وجود ندارد.
                </p>
            @endif
        </div>
    </section>
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/brand/tpl-brand-list.css?v0.27') }}">
@endpush
@push('scripts')
    <script>
        (function () {
            var input = document.querySelector('[data-brand-search]');
            var items = document.querySelectorAll('[data-brand-item]');
            var empty = document.getElementById('brand-list-empty');
            var grid = document.getElementById('brand-list-grid');
            if (!input || !items.length) {
                return;
            }
            function filterBrands() {
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
            input.addEventListener('input', filterBrands);
            if ((input.value || '').trim()) {
                filterBrands();
            }
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
              "name": "برند ها",
              "item": "{{route('brand.list')}}"
        }

              ],
      "name": "menu"
    }
    </script>
@endpush

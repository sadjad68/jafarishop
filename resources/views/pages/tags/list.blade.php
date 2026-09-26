@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "تگ ها ")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "لیست تگ ها")
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $tagCount = count($tags);
        $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'تگ‌ها';
        $showSearch = $tagCount > 8;
    @endphp
    @include('pages._shared.page-banner', [
        'bannerTitleId' => 'tags-page-title',
        'bannerEyebrow' => 'موضوع‌ها',
        'bannerTitle' => $pageTitle,
        'bannerCrumbs' => [
            ['label' => 'خانه', 'url' => route('index')],
            ['label' => 'تگ‌ها'],
        ],
        'bannerStatIcon' => 'bi-tags-fill',
        'bannerStatNum' => $tagCount,
        'bannerStatLabel' => 'تگ',
    ])
    <section class="sk-page" aria-labelledby="tags-page-title">
        <div class="container">
            @if($showSearch)
                <div class="sk-tag-search" role="search">
                    <label class="visually-hidden" for="tag-search">جستجوی تگ</label>
                    <input id="tag-search"
                           type="search"
                           class="form-control"
                           placeholder="جستجوی تگ"
                           autocomplete="off"
                           data-tag-search>
                    <span class="sk-tag-search__icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            @endif
            @if($tagCount > 0)
                <ul class="sk-tag-cloud" id="tag-cloud">
                    @foreach($tags as $tag)
                        <li data-tag-item data-title="{{ mb_strtolower($tag['title'] ?? '') }}">
                            <a href="{{ route('tag.detail', ['url' => $tag['url']]) }}" class="sk-tag-chip">
                                {{ $tag['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <p class="sk-empty" id="tag-list-empty" hidden aria-live="polite">تگی با این نام پیدا نشد.</p>
            @else
                <p class="sk-empty">هنوز تگی برای نمایش وجود ندارد.</p>
            @endif
        </div>
    </section>
@stop
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/product/tpl-product-list.css?v0.38')}}">
@endpush
@push('scripts')
    <script>
        (function () {
            var input = document.querySelector('[data-tag-search]');
            var items = document.querySelectorAll('[data-tag-item]');
            var empty = document.getElementById('tag-list-empty');
            var cloud = document.getElementById('tag-cloud');
            if (!input || !items.length) {
                return;
            }
            function filterTags() {
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
                if (cloud) {
                    cloud.hidden = visible === 0;
                }
                if (empty) {
                    empty.hidden = visible !== 0;
                }
            }
            input.addEventListener('input', filterTags);
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
          "name": "تگ ها",
          "item": "{{{route('tag.list')}}}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

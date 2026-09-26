@extends('layouts.main.master')
@section('title_seo')
    {{'جستجوی '.$search}}
@endsection
@section('description_seo')
    {{'مشاهده نتایج جستجوی '.$search}}
@endsection
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/search/tpl-site-search.css?v0.19')}}">
<link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">
@endpush
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@php
    $totalResults = !empty($sortedResults) ? collect($sortedResults)->sum('count') : 0;
    $tabIcons = [
        'products' => 'bi-bag-fill',
        'categories' => 'bi-grid-fill',
        'brands' => 'bi-award-fill',
        'blogs' => 'bi-journal-richtext',
        'services' => 'bi-tools',
        'portfolios' => 'bi-images',
    ];
@endphp
<section class="search-page">
    <div class="search-page__hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="search-page__breadcrumb">
                <ol class="breadcrumb m-0 justify-content-center">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}" class="d-inline-flex align-items-center gap-1">
                            <i class="bi bi-house d-flex"></i>
                            خانه
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">جستجو</li>
                </ol>
            </nav>
            <h1 class="search-page__title">نتایج جستجو</h1>
            @if(!empty($search))
                <p class="search-page__query-wrap m-0">
                    <span class="search-page__query-label">عبارت:</span>
                    <span class="search-page__query">«{{ $search }}»</span>
                </p>
            @endif
            @if($totalResults > 0)
                <p class="search-page__stats m-0">{{ number_format($totalResults) }} نتیجه یافت شد</p>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="search-page__toolbar" id="scrollToMe">
            <form method="GET" action="{{ route('search.detail') }}" id="search-result-form" class="search-page__form">
                <input type="hidden" name="search_form" value="1">
                <input type="text" name="search" id="search-result-input" class="search-page__input form-control"
                       placeholder="کلمه یا عبارت مورد نظر را بنویسید..." value="{{ $search }}" minlength="3" autocomplete="off">
                <button type="submit" class="search-page__submit btn" aria-label="جستجو">
                    <i class="bi bi-search d-flex"></i>
                </button>
            </form>
            @if(!empty($searchError))
                <p class="search-page__error">
                    <i class="bi bi-exclamation-circle d-flex"></i>
                    {{ $searchError }}
                </p>
            @endif
        </div>
    </div>

    @if(!empty($sortedResults))
        <div class="search-page__tabs tabs-buttons">
            <div class="container">
                <ul class="nav nav-pills search-page__tab-list" id="pills-tab" role="tablist">
                    @foreach ($sortedResults as $key => $result)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link search-page__tab {{ $key === $activeTab ? 'active' : '' }}"
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#{{ $key }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ $key }}"
                                    aria-selected="{{ $key === $activeTab ? 'true' : 'false' }}">
                                <i class="bi {{ $tabIcons[$key] ?? 'bi-search' }} d-flex"></i>
                                <span>{{ $result['title'] }}</span>
                                <span class="search-page__tab-count">{{ $result['count'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="search-page__results result pb-5">
                <div class="tab-content" id="pills-tabContent">
                    @foreach ($sortedResults as $key => $result)
                        <div class="tab-pane fade {{ $key === $activeTab ? 'show active' : '' }}"
                             id="{{ $key }}"
                             role="tabpanel"
                             aria-labelledby="{{ $key }}-tab"
                             tabindex="0">
                            @include($result['view'], ['tab_key' => $key, 'per_page' => $perPage])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif(empty($searchError))
        <div class="container">
            <div class="search-page__empty">
                <span class="search-page__empty-icon">🔍</span>
                <p class="search-page__empty-title">نتیجه‌ای یافت نشد</p>
                <p class="search-page__empty-desc">برای «{{ $search }}» موردی پیدا نکردیم. عبارت دیگری امتحان کنید.</p>
            </div>
        </div>
    @endif
</section>
@stop
@push('scripts')
<script src="{{asset('assets/site/js/search/tpl-site-search.js?v0.01')}}"></script>
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
          "name": "{{$search.' جستجوی '}}",
          "item": "{!! url('/search?search_form=1&search='.$search) !!}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

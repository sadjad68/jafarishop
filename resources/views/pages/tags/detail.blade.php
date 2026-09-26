@extends('layouts.main.master')
@section('robots', @$tag->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$tag->seoTitle ? $tag->seoTitle : $tag->title)
@section('description_seo',@$tag->seoDescription)
@section('image_seo',@$tag->item_image)
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/search/tpl-site-search.css?v0.16')}}">
    <link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">
@endpush
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $productTotal = method_exists($products, 'total') ? $products->total() : count($products);
    @endphp
    @include('pages._shared.page-banner', [
        'bannerTitleId' => 'tag-detail-title',
        'bannerEyebrow' => 'تگ‌ها',
        'bannerTitle' => @$tag->getH1PagesAttribute($tag),
        'bannerCrumbs' => [
            ['label' => 'خانه', 'url' => route('index')],
            ['label' => 'تگ‌ها', 'url' => route('tag.list')],
            ['label' => $tag['title']],
        ],
        'bannerStatIcon' => 'bi-box-seam',
        'bannerStatNum' => $productTotal,
        'bannerStatLabel' => 'محصول',
    ])
    <section class="sk-page">
        <div class="container">
            @include('pages.tags._partials.products')
            @if(!empty($tag['description']))
                <section class="sk-prose" aria-label="درباره این تگ">
                    <p class="sk-prose__eyebrow">درباره این موضوع</p>
                    <div class="sk-prose__body content">
                        {!! $tag['description'] !!}
                    </div>
                </section>
            @endif
        </div>
    </section>
@stop
@push('scripts')
    <script src="{{asset('assets/site/js/search/tpl-site-search.js?v0.02')}}"></script>
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
        },
           {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{$tag['title']}}",
          "item": "{{ route('tag.detail', ['url' => $tag['url']]) }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

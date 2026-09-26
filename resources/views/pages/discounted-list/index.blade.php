@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo', @$seo_data['title_seo'] ? @$seo_data['title_seo'] : ' محصولات شگفت انگیز')
@section('description_seo', @$seo_data['description_seo'] ? @$seo_data['description_seo'] : ' محصولات شگفت انگیز')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}"
        title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
    @php
        $sale_title = @$seo_data->h1 ? $seo_data->h1 : 'محصولات شگفت‌انگیز';
        $sale_total = method_exists($products, 'total') ? $products->total() : count($products);
    @endphp
    <div class="plp-page plp-page--sale">
        <header class="dlp-hero">
            <div class="container">
                <div class="dlp-hero__ticket" data-reveal>
                    <div class="dlp-hero__copy">
                        <p class="dlp-hero__eyebrow">پیشنهاد محدود</p>
                        <h1 id="sale-page-title" class="dlp-hero__title">{{ $sale_title }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}" class="d-flex align-items-center">
                                        <i class="bi bi-house d-flex me-1" aria-hidden="true"></i>
                                        خانه
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">محصولات شگفت‌انگیز</li>
                            </ol>
                        </nav>
                    </div>
                    @if ($sale_total > 0)
                        <div class="dlp-hero__stub" aria-label="@toPersianNumber($sale_total) پیشنهاد فعال">
                            <i class="bi bi-stopwatch dlp-hero__stub-icon" aria-hidden="true"></i>
                            <strong class="dlp-hero__stub-count">@toPersianNumber($sale_total)</strong>
                            <span class="dlp-hero__stub-label">پیشنهاد فعال</span>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <section class="list-product mt-3" aria-labelledby="sale-page-title">
            <div class="container">
                @if (count($products) > 0)
                    <div class="row w-100 m-0 plp-grid lists" data-reveal-group>
                        @foreach ($products as $product)
                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 p-sm-2 p-1" data-reveal>
                                @include('layouts.common.product.product-box', [
                                    'product' => $product,
                                    'show_product_countdown' => true,
                                ])
                            </div>
                        @endforeach
                    </div>
                    <div class="plp-pagination">
                        @component('layouts.common.pagination.default')
                            @slot('paginator', $products)
                        @endcomponent
                    </div>
                @else
                    @include('pages._shared.plp-empty', [
                        'message' => 'در حال حاضر پیشنهاد شگفت‌انگیزی فعال نیست.',
                    ])
                @endif
            </div>
        </section>
    </div>
    @include('pages.first-page._partials.timer-script', ['timer_products' => $products])
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/product/tpl-product-list.css?v0.37') }}">
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
          "name": "محصولات شگفت انگیز",
          "item": "{{route('product.get-discounted-list')}}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

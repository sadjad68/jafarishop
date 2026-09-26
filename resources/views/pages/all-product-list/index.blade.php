@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : $settings['all_product_title'])
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "")
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    <div class="plp-page">
        <header class="plp-header">
            <div class="container">
                <div class="plp-header__inner">
                    <h1 class="plp-header__title font-bold color-title">
                        {{ @$seo_data->h1 ? $seo_data->h1 : $settings['all_product_title'] }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('index') }}" class="d-flex align-items-center">
                                    <i class="bi bi-house d-flex me-1"></i>
                                    خانه
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $settings['all_product_title'] }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </header>

        <section class="list-product mt-3">
            <div class="container">
                @mobile
                    @include('pages.all-product-list._partials.price-mobile-script')
                @else
                    @include('pages.all-product-list._partials.price-desktop-script')
                @endmobile

                <div class="row w-100 m-0 plp-layout" id="app" v-cloak>
                    @include('pages.all-product-list._partials.desktop-filter')
                    @include('pages.all-product-list._partials.mobile-filter')

                    <div class="col-xl-9 col-lg-8 p-lg-2 p-0">
                        <div class="plp-toolbar">
                            @include('pages.all-product-list._partials.sort')
                        </div>

                        <div class="row w-100 m-0 plp-grid lists" v-scroll="scroll">
                            @include('pages.all-product-list._partials.laravel-list')
                            @include('pages.all-product-list._partials.vue-list')
                            <div id="scrollMePlease"></div>
                        </div>

                        <div v-if="scrollable == false">
                            <paginate
                                :initial-page="page - 1"
                                v-model="page"
                                v-if="products.length != 0 && !loading"
                                :page-count="pageCount"
                                :click-handler="handlePageClick"
                                :prev-text="'قبلی'"
                                :next-text="'بعدی'"
                                :container-class="'custom-paginate mt-4'"
                            >
                            </paginate>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('pages.all-product-list._partials.description')
    </div>
@stop
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/product/tpl-product-list.css?v0.37')}}">
    <link rel="stylesheet" href="{{asset('assets/site/css/product/tpl-jquery-ui.css')}}">

    <script src="{{asset('assets/site/js/shared/tpl-jquery.min.js')}}"></script>
    <script src="{{asset('assets/site/js/product/tpl-jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/site/js/product/tpl-jquery.ui.touch-punch.min.js')}}"></script>
@endpush
@push('vue')
    @include('pages.all-product-list._partials.vue')
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
          "name": "{{$settings['all_product_title']}}",
          "item": "{{route('product.get-all')}}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

@extends('layouts.main.master')
@section('robots', @$product_category->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$product_category->seoTitle ? $product_category->seoTitle : $product_category->title,)
@section('description_seo',@$product_category->seoDescription)
@section('image_seo',@$product_category->getImage())
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    @php
        $categoryCrumbs = [
            ['label' => 'خانه', 'url' => route('index')],
        ];
        foreach ($product_category->getAllParents() as $parent) {
            $categoryCrumbs[] = [
                'label' => $parent['title'],
                'url' => \App\Library\SiteUrl::category($parent),
            ];
        }
        $categoryCrumbs[] = ['label' => $product_category['title']];
        $childCount = isset($children) ? count($children) : 0;
    @endphp
    <div class="plp-page">
        @include('pages._shared.page-banner', [
            'bannerTitleId' => 'category-detail-title',
            'bannerEyebrow' => 'مسیر خرید',
            'bannerTitle' => @$product_category->getH1PagesAttribute($product_category),
            'bannerCrumbs' => $categoryCrumbs,
            'bannerStatIcon' => 'bi-grid-fill',
            'bannerStatNum' => $childCount,
            'bannerStatLabel' => 'زیردسته',
        ])

        <section class="list-product">
            <div class="container">
                @include('pages.product-list._partials.upper-categories')
                @include('pages.product-list._partials.price-desktop-script')
                @include('pages.product-list._partials.price-mobile-script')

                <div class="row w-100 m-0 plp-layout" id="app" v-cloak>
                    @include('pages.product-list._partials.desktop-filter')
                    @include('pages.product-list._partials.mobile-filter')

                    <div class="col-xl-9 col-lg-8 p-0 plp-main">
                        <div class="plp-stage">
                            <div class="plp-toolbar">
                                @include('pages.product-list._partials.sort')
                            </div>

                            <div class="row w-100 m-0 plp-grid lists" v-scroll="scroll">
                                @if(count($products) > 0)
                                    @include('pages.product-list._partials.laravel-list')
                                @endif
                                @include('pages.product-list._partials.vue-list')
                                <div id="scrollMePlease"></div>
                                @if(count($products) == 0)
                                    <div class="plp-empty-ssr" v-if="!isFilter && products.length == 0 && !filterLoading && !loading">
                                        @include('pages._shared.plp-empty')
                                    </div>
                                @endif
                            </div>
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

        @include('pages.product-list._partials.description')
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
    @include('pages.product-list._partials.vue')
@endpush
@push('scripts')
    <script>
        let tableList = document.querySelectorAll('.seo-box table');
        tableList.forEach((item) => {
            item.className = "table table-bordered bg-transparent mx-auto table-striped";
            item.outerHTML = `<div class="table-responsive">${item.outerHTML}</div>`
        })
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
        @foreach ($product_category->getAllParents() as $key => $parent)
        {
          "@@type": "ListItem",
          "position": {{$key+2}},
          "name": "{{$parent['title']}}",
          "item": "{{\App\Library\SiteUrl::category($parent)}}"
        },
        @endforeach
          {
          "@@type": "ListItem",
          "position": {{count($product_category->getAllParents())+2}},
          "name": "{{$product_category['title']}}",
          "item": "{{\App\Library\SiteUrl::category($product_category)}}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

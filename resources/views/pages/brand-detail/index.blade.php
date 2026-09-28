@extends('layouts.main.master')
@section('robots', @$brand->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$brand->seoTitle ? $brand->seoTitle : $brand->title)
@section('description_seo',@$brand->seoDescription)
@section('image_seo',@$brand->item_image)
@section('logo')
    <img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}"
         title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
    <div class="plp-page">
        @include('pages._shared.page-banner', [
            'bannerTitleId' => 'brand-detail-title',
            'bannerEyebrow' => 'انتخاب برند',
            'bannerTitle' => @$brand->getH1PagesAttribute($brand),
            'bannerCrumbs' => [
                ['label' => 'خانه', 'url' => route('index')],
                ['label' => 'برندها', 'url' => route('brand.list')],
                ['label' => $brand['title']],
            ],
            'bannerStatLogo' => $brand->item_image,
            'bannerStatLogoAlt' => $brand['title'],
            'bannerStatLabel' => 'برند',
        ])

        <section class="list-product">
            <div class="container">
                @include('pages.brand-detail._partials.price-desktop-script')
                @include('pages.brand-detail._partials.price-mobile-script')

                <div class="row w-100 m-0 plp-layout" id="app" v-cloak>
                    @include('pages.brand-detail._partials.filter-desktop')
                    @include('pages.brand-detail._partials.filter-mobile')

                    <div class="col-xl-9 col-lg-8 p-0 plp-main">
                        <div class="plp-stage">
                            <div class="plp-toolbar">
                                @include('pages.brand-detail._partials.sort')
                            </div>

                            <div class="row w-100 m-0 plp-grid lists" v-scroll="scroll">
                                @if($products->count() > 0)
                                    @include('pages.brand-detail._partials.laravel-list')
                                @endif
                                @include('pages.brand-detail._partials.vue-list')
                                <div id="scrollMePlease"></div>
                                @if($products->count() == 0)
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

        @include('pages.brand-detail._partials.description')
    </div>
@stop
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/site/css/product/tpl-product-list.css?v0.37')}}">
    <link rel="stylesheet" href="{{asset('assets/site/css/product/tpl-jquery-ui.css')}}">

    <script src="{{asset('assets/site/js/shared/tpl-jquery.min.js')}}"></script>
    <script src="{{asset('assets/site/js/product/tpl-jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/site/js/product/tpl-jquery.ui.touch-punch.min.js')}}"></script>
    <script src="{{asset('assets/site/js/product/tpl-product-filter.js')}}"></script>
@endpush
@push('scripts')
    <script src="{{asset('assets/site/js/product/tpl-product-list.js')}}"></script>
@endpush
@push('vue')
    @include('pages.brand-detail._partials.vue')
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
        },
        {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{@$brand['title']}}",
          "item": "{{ \App\Library\SiteUrl::brand($brand) }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

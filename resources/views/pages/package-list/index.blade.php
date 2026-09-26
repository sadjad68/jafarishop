@extends('layouts.main.master')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/packages/tpl-package-list.css?v0.29') }}">
@endpush
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo', @$seo_data['title_seo'] ? @$seo_data['title_seo'] : 'پکیج ها')
@section('description_seo', @$seo_data['description_seo'] ? @$seo_data['description_seo'] : 'پکیج های سالن زیبایی')
@section('logo')
    <img src="{{ $settings['logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}"
        title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
    @include('pages.package-list._partials.header-inner')
    @if ($theme_provider->getValue() == 'theme1')
        @include('layouts.common.package.theme1-rack', [
            'packages' => $packages,
            't1_pkg_allow_empty' => true,
            't1_pkg_tight' => true,
            't1_pkg_title_id' => 'packages-page-title',
        ])
    @else
        <section class="packages list mt-5">
            <div class="container">
                <div class="row w-100 m-0">
                    @forelse($packages as $package)
                        <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6 p-lg-2 p-1">
                            <div class="package-card">
                                <a href="{{ route('package.detail', ['url' => $package['url']]) }}" class="text-start h-rotate">
                                    <div class="d-flex justify-content-end pb-4">
                                        <span class="arrow">
                                            <img src="{{ asset('assets/site/images/left-top-arrow.svg') }}" class="main-icon"
                                                alt="package-icon" title="package-icon">
                                        </span>
                                    </div>
                                    <div class="package-title pb-3">
                                        <p class="font-re m-0">
                                            {{ $package['title'] }}
                                        </p>
                                    </div>
                                    <img src="{{ $package->getImage() }}" class="w-100 main-image" alt="{{ $package['title'] }}"
                                        title="{{ $package['title'] }}">
                                    <ul class="mylist p-0 m-0 mt-3 mb-1 features">
                                        @foreach($package->services->take(4) as $package_service)
                                            <li class=" align-items-center pb-2 font-th">
                                                <img src="{{ asset('assets/site/images/check-success.svg') }}" class="me-2"
                                                    width="20" height="20" alt="{{ $package_service['title'] }}"
                                                    title="{{ $package_service['title'] }}">
                                                {{ $package_service['title'] }}
                                            </li>
                                        @endforeach
                                        @if($package->services->count() > 4)
                                            <li class="d-flex align-items-center font-th gap-1">
                                                مشاهده همه
                                                <i class="bi bi-arrow-left d-flex icon-features"></i>
                                            </li>
                                        @endif
                                    </ul>
                                    <hr class="m-0">
                                    <p class="d-flex m-0 align-items-center main-price">
                                        <span class="me-1 font-num">
                                            {{ $package['discounted_price'] }}
                                        </span>
                                        <img src="{{ asset('assets/site/images/toman.svg') }}" width="22" height="22" alt="">
                                    </p>
                                    @if ($package['price'] != 0)
                                        <p class="d-flex m-0 align-items-center old-price">
                                            <del class="me-1 font-num-r">
                                                {{ $package['price'] }}
                                            </del>
                                            <span>تومان</span>
                                        </p>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="sk-empty">هنوز پکیجی برای نمایش وجود ندارد.</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endif
@stop
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
          "name": "پکیج ها",
          "item": "{{route('package.list')}}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

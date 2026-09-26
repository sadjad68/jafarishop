@extends('layouts.main.master')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/site/css/packages/tpl-package-detail.css?v0.05') }}">
@endpush
@section('robots', @$package->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo', @$package->seoTitle ? $package->seoTitle : $package->title)
@section('description_seo', @$package->seoDescription)
@section('image_seo', @$package->getImage())
@section('logo')
<img src="{{ $settings['logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
    @include('pages.package-detail._partials.header-inner')
    <section class="pkg-dossier t1-section t1-section--tight" aria-labelledby="package-detail-title">
        <div class="container">
            <div class="pkg-dossier__media" data-reveal>
                <img src="{{ $package->getImage() }}"
                     alt="{{ $package['title'] }}"
                     title="{{ $package['title'] }}"
                     width="800"
                     height="360">
            </div>
            <div class="pkg-dossier__layout">
                @include('pages.package-detail._partials.description')
                @include('pages.package-detail._partials.sidebar')
            </div>
        </div>
    </section>
    <div class="pkg-stub-dock d-lg-none">
        @include('pages.package-detail._partials.package-price', ['pkgDock' => true])
    </div>
@stop
@push('scripts')
    <script>
        document.querySelectorAll('.pkg-dossier__prose table').forEach(function (item) {
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
           "name": "پکیج ها",
          "item": "{{route('package.list')}}"
        },
           {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{$package['title']}}",
          "item": "{{ route('package.detail', ['url' => $package['url']]) }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

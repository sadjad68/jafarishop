@extends('layouts.main.master')
@section('robots', @$page->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$page->seoTitle ? $page->seoTitle : $page->title,)
@section('description_seo',@$page->seoDescription)
@section('logo')
    <img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.static-page._partials.header-inner')
<section class="about-us pt-md-5 pt-3">
    <div class="px-xxl-5 px-xl-4 px-lg-3">
        <div class="about-inner p-lg-5 p-4" style="height: unset !important; ">
            <div id="videoWrapper" class="title-section mb-sm-5 mb-4 m-auto p-lg-3 p-0 seo-box">
                {!! @$page['description'] !!}
            </div>
        </div>
    </div>
</section>
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/us/tpl-about-us.css?v0.07')}}">
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
          "name": "{{$page['title']}}",
          "item": "{{ route('page.detail', ['url' => $page['url']]) }}"
        }
              ],
      "name": "menu"
    }
    </script>

@endpush

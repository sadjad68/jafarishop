@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "درباره ما")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "درباره ما بیشتر بدانید")
@section('logo')
<img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.about-us._partials.header-inner')
<section class="sk-page" id="about_us_branch">
    <div class="container">
        <article class="sk-prose sk-prose--page discraption-about-us" aria-labelledby="about-page-title">
            <p class="sk-prose__eyebrow">داستان ما</p>
            <div class="sk-prose__body content">
                {!! @$settings['about_us'] !!}
            </div>
        </article>
    </div>
</section>
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/us/tpl-about-us.css?v0.07')}}">
@endpush
@push('vue')
@include('layouts.main.blocks.main-vue',['element_id'=>'about_us_branch'])
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
          "name": "{{@$seo_data->h1  ? $seo_data->h1 : 'درباره ما'}}",
          "item": "{{ route('us.about') }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

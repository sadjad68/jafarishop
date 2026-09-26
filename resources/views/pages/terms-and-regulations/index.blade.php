@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "قوانین و مقررات")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "قوانین و مقررات")
@section('logo')
<img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.terms-and-regulations._partials.header-inner')
<section class="sk-page" id="terms_and_regulations_branch">
    <div class="container">
        <article class="sk-prose sk-prose--page discraption-about-us" aria-labelledby="terms-page-title">
            <p class="sk-prose__eyebrow">شرایط استفاده</p>
            <div class="sk-prose__body content">
                {!! @$settings['terms_and_conditions'] !!}
            </div>
        </article>
    </div>
</section>
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/us/tpl-about-us.css?v0.07')}}">
@endpush
@push('vue')
    @include('layouts.main.blocks.main-vue',['element_id'=>'terms_and_regulations_branch'])
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
          "name": "{{@$seo_data->h1  ? $seo_data->h1 : (@$settings['terms_and_conditions_page_title'] ?? 'قوانین و مقررات')}}",
          "item": "{{ route('us.terms') }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

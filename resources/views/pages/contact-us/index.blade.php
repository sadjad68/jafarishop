@extends('layouts.main.master')
@section('robots', @$seo_data['noindex'] == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo',@$seo_data['title_seo'] ? @$seo_data['title_seo'] : "تماس با ما")
@section('description_seo',@$seo_data['description_seo'] ? @$seo_data['description_seo'] : "با ما در تماس باشید")
@section('logo')
<img src="{{$settings['logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.contact-us._partials.header-inner')
<section class="sk-page conatct-us">
    <div class="container">
        <div class="sk-contact">
            @include('pages.contact-us._partials.contact-info')
            @include('pages.contact-us._partials.contact-form')
        </div>
    </div>
</section>
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/us/tpl-contact-us.css?v0.05')}}">
<script src="{{asset('assets/site/js/tpl-sweetalert2.all.min.js')}}"></script>
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
          "name": "{{@$seo_data->h1  ? $seo_data->h1 : 'تماس باما'}}",
          "item": "{{ route('us.contact') }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush

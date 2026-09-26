@extends('layouts.main.master')
@php
    $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
@endphp
@section('logo')
    @if($theme_provider->hasSection('siteSections','logo'))
<img src="{{ $settings['footer_logo'] ?? $settings['logo'] }}"  width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
    @endif
@endsection
@section('content')
    @php
        $slider_h1_position = $settings['slider_h1_position'] ?? 'above';
    @endphp
    @if (trim($slider_h1_position, '\'"') === 'above')
        @include('pages.first-page._partials.theme1.home-h1')
    @endif
@include("pages.first-page._partials." . $theme_provider->getValue() . ".header")
    @if (trim($slider_h1_position, '\'"') === 'below')
        @include('pages.first-page._partials.theme1.home-h1')
    @endif
@include("pages.first-page._partials." . $theme_provider->getValue() . ".services")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".samples")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".about-us")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".category")
@include('pages.first-page._partials.products')
@include("pages.first-page._partials." . $theme_provider->getValue() . ".discounted")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".tags")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".gallery")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".team")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".certificates")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".blogs")
@include("pages.first-page._partials." . $theme_provider->getValue() . ".packages")
@include('layouts.common.sweetalert')
@stop
@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/index/tpl-theme1-home.min.css?v0.60')}}">
<link rel="stylesheet" href="{{asset('assets/site/css/blogs/tpl-blog-list.css?v0.33')}}">
@endpush
@push('scripts')
<script src="{{asset('assets/site/js/index/tpl-home.js?v1.09')}}"></script>
@endpush
@push('schema')
<script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Organization",
      "url": "{{url('/')}}",
      "logo": "{{@$settings['logo']}}",
      "name": "{{@$default_seo['title_seo']}}",
      "image": "{{@$settings['logo']}}",
      "email": "{{@$settings['email']}}",
      "description": "{{@$default_seo['description_seo']}}",
    @if(count($branches) > 0)
      "address": "{{@$branches[0]['address']}}",
    @endif
      "telephone": "{{@$settings['main_phone_number']}}" @if(count($socials) > 0),
      "sameAs": [
    @foreach($socials as $social)
        "{!! $social['link'] !!}"@if(!$loop->last),@endif
        @endforeach
      ]
    @endif
    }
</script>
@endpush

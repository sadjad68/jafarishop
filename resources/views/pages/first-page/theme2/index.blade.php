@extends('layouts.main.master')
@php
    $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
@endphp
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/shop/tpl-theme2-home.min.css?v0.71') }}">
@endpush
@section('content')
    @php
        $slider_h1_position = $settings['slider_h1_position'] ?? 'above';
    @endphp
        @if (trim($slider_h1_position, '\'"') === 'above')
        @include('pages.first-page._partials.theme2.home-h1')
    @endif
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".header")
        @if (trim($slider_h1_position, '\'"') === 'below')
            @include('pages.first-page._partials.theme2.home-h1')
    @endif
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".banner")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".discounted")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".banners")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".category")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".products")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".banner-2")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".tags")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".banners-last")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".banner-fourth")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".brands")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".blogs")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".banners-end")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".services")
    @include("pages.first-page._partials." . $theme_provider->getValue() . ".about-us")
    @include('layouts.common.sweetalert')
@stop
@push('scripts')
    <script src="{{ asset('assets/site/js/index/tpl-home.js?v1.03') }}"></script>
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

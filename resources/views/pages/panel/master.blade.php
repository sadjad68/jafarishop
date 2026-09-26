<!DOCTYPE html>
<html lang="fa" dir="rtl" class="is-site-loading">
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/panel/tpl-user-panel.css?v=2.3.4') }}">
@endpush
@include('layouts.main.blocks.head')

@php
    $theme_provider = app(\App\Modules\General\Helper\ThemeProvider::class);
@endphp
<body class="theme-{{ $theme_provider->getValue() }}">
@include('layouts.main.blocks.page-loader')
@include("layouts.main.blocks." . $theme_provider->getValue() . ".menu")
    @if($themes['menu_type'] == "mega_menu")
    @include('layouts.main.blocks.mega-menu')
    @endif
@if($theme_provider->hasSection('siteSections','search') && $theme_provider->getValue() !== 'theme1')
    @include("layouts.main.blocks." . $theme_provider->getValue() . ".search")
@endif

    <section class="panel panel-shell mx-md-4 mt-md-3 mt-4 mb-5">
        <div class="panel-shell__ambient" aria-hidden="true">
            <span class="panel-shell__orb panel-shell__orb--1"></span>
            <span class="panel-shell__orb panel-shell__orb--2"></span>
        </div>
        <div class="container position-relative">
            <div class="panel-shell__grid">
                <aside class="panel-shell__sidebar">
                    @include('pages.panel.blocks.sidebar')
                    @include('pages.panel.blocks.sidebar-mobile')
                </aside>
                <main class="panel-shell__main">
                    @yield('content')
                </main>
            </div>
        </div>
    </section>

@include("layouts.main.blocks." . $theme_provider->getValue() . ".footer")
    @include('layouts.main.blocks.script')
</body>

</html>

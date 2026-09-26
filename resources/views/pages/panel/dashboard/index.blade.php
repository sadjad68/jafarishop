@extends('pages.panel.master')
@section('dashboard','active')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.panel._partials.welcome-hero')

<div class="content mt-3">
    <div class="row w-100 m-0 g-3">
        @include('pages.panel.dashboard._partials.personal-info')
    </div>
    @include('pages.panel.dashboard._partials.recent-orders')
</div>
@endsection

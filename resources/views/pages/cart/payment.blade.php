@extends('layouts.main.master')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('title_seo')پرداخت@endsection
@section('description_seo')پرداخت@endsection
@section('content')
<section class="cart cart-page mx-md-4 mt-md-3 mt-4 mb-5">
    <div class="container" id="app" v-cloak>
        @include('pages.cart._partials.steps', ['activeStep' => 3])

        <div class="checkout row w-100 m-0">
            @include('pages.cart._partials.payment.item-list')
            @include('pages.cart._partials.payment.price-box')
        </div>
    </div>
</section>
@include('layouts.common.sweetalert')
@stop
@push('styles')
    <style>
        @media(max-width:576px) {
            .btn-to-top {
                bottom: 9rem;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/site/css/cart/tpl-checkout.css?v.20') }}">
    <script src="{{ asset('assets/site/js/tpl-sweetalert2.all.min.js') }}"></script>
@endpush
@push('scripts')
    <script src="{{ asset('assets/site/js/cart/tpl-checkout.js') }}"></script>
@endpush
@push('vue')
    @include('pages.cart._partials.payment.vue')
@endpush

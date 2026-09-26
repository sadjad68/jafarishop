@extends('layouts.main.master')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('title_seo')پرداخت ناموفق@endsection
@section('description_seo')پرداخت ناموفق@endsection
@section('content')
<section class="cart cart-result">
    <div class="container">
        <div class="cart-result__card">
            <div class="cart-result__icon cart-result__icon--fail">
                <img src="{{ asset('assets/site/images/Frame 24.png') }}" alt="پرداخت ناموفق" loading="lazy">
            </div>
            <h1 class="cart-result__title cart-result__title--fail">پرداخت انجام نشد</h1>
            <p class="cart-result__text">در صورت کسر وجه، مبلغ طی ۷۲ ساعت به حساب شما بازمی‌گردد. می‌توانید دوباره تلاش کنید.</p>
            <a href="{{ route('basket.cart') }}" class="sk-cta">
                <i class="bi bi-cart3"></i>
                بازگشت به سبد خرید
            </a>
        </div>
    </div>
</section>
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/cart/tpl-checkout.css?v.20') }}">
@endpush

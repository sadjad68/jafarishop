@extends('layouts.main.master')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('title_seo'){{ !empty($isPendingCardToCard) ? 'ثبت پرداخت' : 'پرداخت موفق' }}@endsection
@section('description_seo'){{ !empty($isPendingCardToCard) ? 'ثبت پرداخت' : 'پرداخت موفق' }}@endsection
@section('content')
<section class="cart cart-result">
    <div class="container">
        <div class="cart-result__card">
            <div class="cart-result__icon cart-result__icon--success">
                <img src="{{ asset('assets/site/images/Frame 25.png') }}" alt="{{ !empty($isPendingCardToCard) ? 'ثبت پرداخت' : 'پرداخت موفق' }}" loading="lazy">
            </div>

            @if(!empty($isPendingCardToCard))
                <h1 class="cart-result__title cart-result__title--success">پرداخت انجام شد</h1>
                <p class="cart-result__text">
                    فیش واریزی شما دریافت شد. پس از تأیید پرداخت، سفارش شما وارد مرحله بررسی می‌شود.
                </p>
            @else
                <h1 class="cart-result__title cart-result__title--success">سفارش شما با موفقیت ثبت شد</h1>
                <p class="cart-result__text">از خرید شما سپاسگزاریم. جزئیات سفارش را می‌توانید در پنل کاربری ببینید.</p>
            @endif

            <a href="{{ route('panel.order-detail', ['id' => $order->id]) }}" class="sk-cta">
                <i class="bi bi-receipt"></i>
                مشاهده جزئیات سفارش
            </a>
        </div>
    </div>
</section>
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/cart/tpl-checkout.css?v.20') }}">
@endpush
@if (!empty($ecommerce_purchase))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof window.EcommerceTracking === 'undefined') {
                    return;
                }
                var transactionId = @json($ecommerce_purchase['transaction_id']);
                window.EcommerceTracking.pushOncePerSession(
                    'purchase_' + transactionId,
                    'purchase',
                    @json($ecommerce_purchase)
                );
            });
        </script>
    @endpush
@endif

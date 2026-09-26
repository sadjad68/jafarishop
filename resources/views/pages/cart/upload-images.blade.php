@extends('layouts.main.master')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
@php
    $isCardToCard = @$order->bank->bank_type === 'cardtocard';
    $bankConfig = json_decode(@$order->bank->config ?: '{}', true) ?: [];
    $cardNumber = $bankConfig['card_number'] ?? null;
    $shabaNumber = $isCardToCard
        ? ($bankConfig['shaba_number'] ?? null)
        : (@$settings['shaba_number'] ?? null);
    $transferAmount = $isCardToCard
        ? @$order->payment_price
        : @$order->remaining_price;
@endphp
<section class="cart cart-result">
    <div class="container">
        <div class="cart-result__card cart-result__card--wide">
            <div class="cart-result__icon">
                <img src="{{ asset('assets/site/images/checking.png') }}" width="90" alt="در انتظار تأیید" loading="lazy">
            </div>

            <h1 class="cart-result__title cart-result__title--success">
                @if($isCardToCard)
                    سفارش ثبت شد — فیش را ارسال کنید
                @else
                    پرداخت با موفقیت انجام شد
                @endif
            </h1>

            <div class="cart-alert cart-alert--info text-start">
                برای ارسال فیش واریز به مبلغ
                <strong class="font-num">{{ number_format($transferAmount) }}</strong>
                تومان
                @if($cardNumber)
                    به شماره کارت <strong class="font-num">{{ $cardNumber }}</strong>
                @endif
                @if($shabaNumber)
                    و شبای <strong class="font-num">{{ $shabaNumber }}</strong>
                @endif
                از فرم زیر استفاده کنید.
                @if($isCardToCard && $order->bank->reservation_expire_minutes)
                    <div class="cart-alert__warn mt-2">
                        مهلت واریز و بارگذاری فیش: حداکثر {{ $order->bank->reservation_expire_minutes }} دقیقه
                    </div>
                @endif
            </div>

            <form action="{{ route('panel.store-images', ['id' => $order->id]) }}" method="POST" enctype="multipart/form-data" class="cart-upload-form w-100 text-start">
                @csrf
                <div class="cart-field">
                    <label for="file" class="cart-field__label">آپلود فیش واریز</label>
                    <input type="file" id="file" class="cart-field__file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                <button type="submit" class="sk-cta w-100">
                    <i class="bi bi-upload"></i>
                    ارسال فیش
                </button>
            </form>
        </div>
    </div>
</section>
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/site/css/cart/tpl-checkout.css?v.20') }}">
@endpush

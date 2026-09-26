@extends('pages.panel.master')
@section('order','active')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@php
    $orders = $user->ordersWithDeposit;
    $totalSpent = $orders->sum('payment_price');
@endphp

<div class="order-page">
    @include('pages.panel._partials.page-header', [
        'icon' => 'bi-bag-check',
        'title' => 'سفارشات من',
        'subtitle' => count($orders) . ' سفارش · ' . number_format($totalSpent) . ' تومان مجموع خرید',
    ])

    @if(count($orders) > 0)
        <div class="panel-order-stats mb-3">
            <div class="panel-order-stats__item">
                <span class="panel-order-stats__value font-num-r">{{ count($orders) }}</span>
                <span class="panel-order-stats__label">سفارش</span>
            </div>
            <div class="panel-order-stats__item">
                <span class="panel-order-stats__value font-num-r">{{ $orders->sum(fn($o) => $o->items->count()) }}</span>
                <span class="panel-order-stats__label">قلم کالا</span>
            </div>
            <div class="panel-order-stats__item panel-order-stats__item--accent">
                <span class="panel-order-stats__value font-num-r">{{ number_format($totalSpent) }}</span>
                <span class="panel-order-stats__label">تومان خرید</span>
            </div>
        </div>

        <div class="panel-order-list">
            @foreach($orders as $order)
                @include('pages.panel.order._partials.list-card', ['order' => $order])
            @endforeach
        </div>
    @else
        <div class="panel-card panel-card--flush">
            <div class="panel-empty py-5">
                <div class="panel-empty__icon"><i class="bi bi-bag-x"></i></div>
                <img class="w-100 mt-3" style="max-width: 180px;" src="{{ asset('assets/site/images/order-cms.svg') }}" alt="">
                <p class="font-bold m-0 mt-4">هنوز هیچ سفارشی ندادید</p>
                <p class="small text-muted mt-2">اولین خرید خود را شروع کنید</p>
                <a href="{{ route('product.get-all') }}" class="sk-cta mt-3" target="_blank">مشاهده محصولات</a>
            </div>
        </div>
    @endif
</div>
@endsection

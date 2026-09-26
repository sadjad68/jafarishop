@php
    $paymentBadge = $isAwaitingPaymentVerification ?? false
        ? '<span class="panel-badge panel-badge--amber">' . e($order->status['title'] ?? '') . '</span>'
        : '<span class="panel-badge" style="--badge-color:' . e($paymentStatusColor ?? 'var(--color-one)') . '">' . e($order->status['title'] ?? '') . '</span>';
@endphp
<div class="panel-order-hero">
    <div class="panel-order-hero__main">
        <div class="panel-order-hero__id-wrap">
            <span class="panel-order-hero__eyebrow">شماره سفارش</span>
            <span class="panel-order-hero__id font-num-r">#{{ $order->id }}</span>
        </div>
        @if(@$order->date)
            <div class="panel-order-hero__date">
                <i class="bi bi-calendar3"></i>
                <span class="font-num-r">{{ $order->date }}</span>
                @if(@$order->time)
                    <span class="panel-order-hero__time font-num-r">{{ $order->time }}</span>
                @endif
            </div>
        @endif
    </div>
    <div class="panel-order-hero__statuses">
        <span class="panel-badge" style="--badge-color: {{ $shippingStatusColor ?? 'var(--color-one)' }}">
            <i class="bi bi-truck"></i>
            {{ @$order->shipping_status->title }}
        </span>
        @if(@$order->status)
            {!! $paymentBadge !!}
        @endif
    </div>
    <div class="panel-order-hero__total">
        <span class="panel-order-hero__total-label">مبلغ پرداختی</span>
        <strong class="panel-order-hero__total-value font-num-r">{{ number_format(intval($order->payment_price)) }} <small>تومان</small></strong>
    </div>
</div>

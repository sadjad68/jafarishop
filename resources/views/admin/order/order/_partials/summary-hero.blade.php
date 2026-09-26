@php
    $paymentBadgeClass = $data->order_status === 'wait_for_verification'
        ? 'payment-status-awaiting-verification'
        : 'bg-label-' . (@$data->status['badge'] ?? 'secondary');
@endphp
<div class="admin-order-hero">
    <div class="admin-order-hero__main">
        <div class="admin-order-hero__id-wrap">
            <span class="admin-order-hero__eyebrow">جزئیات سفارش</span>
            <span class="admin-order-hero__id font-num-r">#{{ $data->id }}</span>
        </div>
        @if(@$data->date)
            <div class="admin-order-hero__date">
                <i class="bi bi-calendar3"></i>
                <span class="font-num-r">{{ $data->date }}</span>
                @if(@$data->time)
                    <span class="admin-order-hero__time font-num-r">{{ $data->time }}</span>
                @endif
            </div>
        @endif
        @if(!empty($data->torob_clid))
            <span class="badge bg-label-success admin-order-hero__source">منبع: ترب</span>
        @endif
    </div>

    <div class="admin-order-hero__statuses">
        @if(!in_array($data->order_status, ['paying', 'unpaid']) && @$data->shipping_status)
            <span class="admin-order-badge" style="--badge-color: {{ @$data->shipping_status->color ?? 'var(--admin-accent)' }}">
                <i class="bi bi-truck"></i>
                {{ @$data->shipping_status->title }}
            </span>
        @endif
        @if(@$data->status)
            <span class="badge {{ $paymentBadgeClass }} admin-order-hero__payment-badge">
                {{ @$data->status['title'] }}
            </span>
        @endif
        {!! $data->hasReturnItem() !!}
    </div>

    <div class="admin-order-hero__total">
        <span class="admin-order-hero__total-label">مبلغ پرداختی</span>
        <strong class="admin-order-hero__total-value font-num-r">
            {{ number_format(intval($data->payment_price)) }}
            <small>تومان</small>
        </strong>
    </div>
</div>

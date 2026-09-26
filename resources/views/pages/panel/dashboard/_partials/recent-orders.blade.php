@php
    $recentOrders = $user->ordersWithDeposit->take(3);
@endphp
@if($recentOrders->count() > 0)
<div class="panel-card panel-card--flush mt-3">
    <div class="panel-card__head panel-card__head--split px-3 pt-3 pb-0 border-0">
        <div>
            <h3 class="panel-card__title" style="font-size: 1.05rem;">
                <span class="panel-card__title-icon"><i class="bi bi-clock-history"></i></span>
                آخرین سفارش‌ها
            </h3>
            <p class="panel-card__meta">۳ سفارش اخیر شما</p>
        </div>
        <a href="{{ route('panel.orders') }}" class="panel-stat__link mb-3">همه سفارشات</a>
    </div>
    <div class="panel-recent-orders px-3 pb-3">
        @foreach($recentOrders as $order)
            <a href="{{ route('panel.order-detail', ['id' => $order->id]) }}" class="panel-order-row">
                <div class="panel-order-row__main">
                    <span class="panel-order-row__id font-num-r">#{{ $order->id }}</span>
                    <ul class="panel-order-row__thumbs p-0 m-0">
                        @foreach($order->items->take(4) as $item)
                            <li>
                                <img src="{{ @$item->product_variant_id ? (\App\Modules\Product\Services\VariantService::getProductImagesSizeSeperated($item->product_variant->images)[0]['image_medium'] ?? '/assets/notfounds/product-img.jpg') : @$item->product->getImage() }}"
                                     alt="" loading="lazy">
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel-order-row__meta">
                    <span class="panel-order-row__price font-num-r">{{ number_format($order->payment_price) }} تومان</span>
                    @if($order->order_status === 'wait_for_verification')
                        <span class="panel-badge panel-badge--amber">{{ @$order->status['title'] }}</span>
                    @else
                        <span class="panel-badge" style="--badge-color: {{ @$order->shipping_status->color }}">{{ @$order->shipping_status->title }}</span>
                    @endif
                </div>
                <i class="bi bi-chevron-left panel-order-row__chevron"></i>
            </a>
        @endforeach
    </div>
</div>
@endif

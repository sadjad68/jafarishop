@php
    $itemCount = $order->items->count();
    $extraThumbs = max(0, $itemCount - 4);
@endphp
<a href="{{ route('panel.order-detail', ['id' => $order->id]) }}" class="panel-order-card">
    <div class="panel-order-card__top">
        <div class="panel-order-card__id">
            <span class="panel-order-card__hash">#</span>
            <span class="font-num-r">{{ $order->id }}</span>
        </div>
        <div class="panel-order-card__badges">
            @if($order->order_status === 'wait_for_verification')
                <span class="panel-badge panel-badge--amber">{{ @$order->status['title'] }}</span>
            @else
                @if(@$order->status)
                    <span class="panel-badge panel-badge--muted">{{ @$order->status['title'] }}</span>
                @endif
                <span class="panel-badge" style="--badge-color: {{ @$order->shipping_status->color }}">{{ @$order->shipping_status->title }}</span>
            @endif
        </div>
    </div>

    <div class="panel-order-card__body">
        <ul class="panel-order-card__thumbs">
            @foreach($order->items->take(4) as $item)
                <li>
                    <img src="{{ @$item->product_variant_id ? (\App\Modules\Product\Services\VariantService::getProductImagesSizeSeperated($item->product_variant->images)[0]['image_medium'] ?? '/assets/notfounds/product-img.jpg') : @$item->product->getImage() }}"
                         alt="{{ @$item->product->title }}" loading="lazy">
                </li>
            @endforeach
            @if($extraThumbs > 0)
                <li class="panel-order-card__more font-num-r">+{{ $extraThumbs }}</li>
            @endif
        </ul>
        <div class="panel-order-card__info">
            <p class="panel-order-card__items">{{ $itemCount }} قلم کالا</p>
            @if(@$order->date)
                <p class="panel-order-card__date font-num-r"><i class="bi bi-calendar3"></i> {{ $order->date }}</p>
            @endif
        </div>
    </div>

    <div class="panel-order-card__footer">
        <span class="panel-order-card__price font-num-r">{{ number_format($order->payment_price) }} <small>تومان</small></span>
        <span class="panel-order-card__cta">
            جزئیات
            <i class="bi bi-arrow-left"></i>
        </span>
    </div>
</a>

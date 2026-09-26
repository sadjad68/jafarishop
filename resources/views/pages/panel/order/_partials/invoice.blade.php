<div class="panel-invoice" id="factor">
    <div class="panel-invoice__head">
        <div>
            <h3 class="panel-invoice__title">
                <i class="bi bi-receipt"></i>
                فاکتور سفارش
            </h3>
            <p class="panel-invoice__meta">{{ count($order->items) }} قلم کالا</p>
        </div>
    </div>

    {{-- Mobile: product cards --}}
    <div class="panel-invoice__mobile d-lg-none">
        @foreach($order->items as $key => $row)
            @php
                $imageUrl = @$row->product_variant_id
                    ? (\App\Modules\Product\Services\VariantService::getProductImagesSizeSeperated(@$row->product_variant->images)[0]['image_medium'] ?? @$row->product->getImage())
                    : @$row->product->getImage();
                $unitPrice = number_format(intval($row->discounted_price) ?: intval($row->price));
                $lineTotal = number_format($row->discounted_price ? intval($row->discounted_price) * $row->quantity : intval($row->price) * $row->quantity);
            @endphp
            <article class="panel-invoice-item">
                <div class="panel-invoice-item__img">
                    <img src="{{ $imageUrl }}" alt="">
                    <span class="panel-invoice-item__index font-num-r">{{ $key + 1 }}</span>
                </div>
                <div class="panel-invoice-item__body">
                    <a href="{{ \App\Library\SiteUrl::product(@$row->product) }}" target="_blank" class="panel-invoice-item__title">
                        {{ @$row->product->title }}
                    </a>
                    @foreach(@$row->product_variant->specifications ?? [] as $specification)
                        <span class="panel-invoice-item__spec">
                            {{ @$specification->parent->title }}: {{ @$specification->title }}
                        </span>
                    @endforeach
                    <div class="panel-invoice-item__foot">
                        <span class="font-num-r">{{ $row->quantity }} × {{ $unitPrice }}</span>
                        <strong class="font-num-r">{{ $lineTotal }} تومان</strong>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Desktop: table --}}
    <div class="panel-invoice__table-wrap d-none d-lg-block">
        <table class="panel-invoice__table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th class="text-start">محصول</th>
                    <th>تصویر</th>
                    <th>تعداد</th>
                    <th>قیمت واحد</th>
                    <th>قیمت کل</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $key => $row)
                    @php
                        $imageUrl = @$row->product_variant_id
                            ? (\App\Modules\Product\Services\VariantService::getProductImagesSizeSeperated(@$row->product_variant->images)[0]['image_medium'] ?? @$row->product->getImage())
                            : @$row->product->getImage();
                    @endphp
                    <tr>
                        <td><span class="panel-invoice__index font-num-r">{{ $key + 1 }}</span></td>
                        <td class="text-start">
                            <a href="{{ \App\Library\SiteUrl::product(@$row->product) }}" target="_blank" class="panel-invoice__product-link">
                                {{ @$row->product->title }}
                            </a>
                            <div class="panel-invoice__specs">
                                @foreach(@$row->product_variant->specifications ?? [] as $specification)
                                    <span class="panel-invoice__spec">
                                        @if(@$specification->parent->is_color)
                                            <i class="panel-invoice__color" style="background: {{ @$specification->color_code ?? '#fff' }}"></i>
                                        @endif
                                        {{ @$specification->parent->title }}: {{ @$specification->title }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td><img src="{{ $imageUrl }}" alt="" class="panel-invoice__thumb"></td>
                        <td><span class="font-num-r fw-bold">{{ @$row->quantity }}</span></td>
                        <td>
                            <span class="font-num-r">{{ number_format(intval($row->discounted_price) ?: intval($row->price)) }}</span>
                            @if(intval($row->discounted_price) != 0 && $row->discounted_price != $row->price)
                                <del class="panel-invoice__old-price font-num-r">{{ number_format(intval($row->price)) }}</del>
                            @endif
                        </td>
                        <td><strong class="font-num-r">{{ number_format($row->discounted_price ? intval($row->discounted_price) * $row->quantity : intval($row->price) * $row->quantity) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="panel-invoice__summary">
        <div class="panel-invoice__summary-row">
            <span>جمع کل</span>
            <span class="font-num-r">{{ number_format(intval($order->total_price)) }} تومان</span>
        </div>
        <div class="panel-invoice__summary-row">
            <span>هزینه ارسال</span>
            <span class="font-num-r">{{ $order->shipping_name }} <small class="text-muted">({{ @$order->freight_balance_name['title'] }})</small></span>
        </div>
        <div class="panel-invoice__summary-row">
            <span>تخفیف</span>
            <span class="font-num-r">
                @if($order->discount_id)
                    ({{ number_format(intval($order->discount_price)) }} تومان)
                @else
                    ندارد
                @endif
            </span>
        </div>
        <div class="panel-invoice__summary-row">
            <span>مالیات</span>
            <span class="font-num-r">
                @if(intval($order->current_tax) != 0)
                    {{ number_format(intval($order->tax_price)) }} تومان ({{ $order->current_tax }}٪)
                @else
                    ندارد
                @endif
            </span>
        </div>
        <div class="panel-invoice__summary-row panel-invoice__summary-row--total">
            <span>مبلغ پرداختی</span>
            <strong class="font-num-r">{{ number_format(intval($order->payment_price)) }} تومان</strong>
        </div>
    </div>
</div>

@if (intval($product['stock']) > 0)
    @if (intval($product['final_price']) != 0)
        <div class="price-block mt-2">
            <div class="price-block__prices">
                @if ($product['discounted_price'] && @$product->percent)
                    <div class="price-block__compare">
                        <span class="price-block__discount">@toPersianNumber($product->percent)٪</span>
                        <span class="price-block__old">@toPersianNumber(number_format($product['price']))</span>
                    </div>
                @endif
                <div class="price-block__current">
                    <span class="price-block__amount">@toPersianNumber(number_format($product['final_price']))</span>
                    <span class="price-block__currency">تومان</span>
                </div>
            </div>
        </div>
    @else
        <div class="product-status mt-2">
            <span class="product-status__badge product-status__badge--call">تماس بگیرید</span>
        </div>
    @endif
@else
    <div class="product-status mt-2">
        <span class="product-status__badge product-status__badge--out">ناموجود</span>
    </div>
@endif

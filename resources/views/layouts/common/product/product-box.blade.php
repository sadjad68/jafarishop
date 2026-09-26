@if ($theme_provider->getValue() == 'theme1')
    @include('layouts.common.product.theme1-product-box')
@else
<a href="{{ \App\Library\SiteUrl::product($product) }}" class="product-card-link" target="_blank">
    <article class="product-card{{ intval($product['stock']) <= 0 ? ' product-card--out' : '' }}">
        <div class="product-card__media">
            @if (!empty($show_product_countdown))
                <ul class="countdown" dir="ltr" aria-label="زمان باقی‌مانده پیشنهاد">
                    <li>
                        <span class="countdown__value" id="days{{ $product['id'] }}"></span>
                        <span class="countdown__unit">روز</span>
                    </li>
                    <li>
                        <span class="countdown__value" id="hours{{ $product['id'] }}"></span>
                        <span class="countdown__unit">ساعت</span>
                    </li>
                    <li>
                        <span class="countdown__value" id="minutes{{ $product['id'] }}"></span>
                        <span class="countdown__unit">دقیقه</span>
                    </li>
                    <li>
                        <span class="countdown__value" id="seconds{{ $product['id'] }}"></span>
                        <span class="countdown__unit">ثانیه</span>
                    </li>
                </ul>
            @endif
            @if ($product->hasVariants())
                <span class="product-card__badge product-card__badge--variant" aria-label="محصول دارای گزینه">
                    <i class="bi bi-sliders" aria-hidden="true"></i>
                </span>
            @endif
            <img src="{{ $product->getImage('medium') }}" class="product-card__img" alt="{{ $product['title'] }}"
                title="{{ $product['title'] }}" loading="lazy" width="300" height="300">
        </div>
        <div class="product-card__body">
            <h3 class="product-card__title">{{ $product['title'] }}</h3>
            @include('layouts.common.product._price-block', ['product' => $product])
        </div>
    </article>
</a>
@endif

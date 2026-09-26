@php
    $t1Out = intval($product['stock']) <= 0;
    $t1Priced = ! $t1Out && intval($product['final_price']) != 0;
    $t1Off = $t1Priced && $product['discounted_price'] && @$product->percent;
@endphp
<a href="{{ \App\Library\SiteUrl::product($product) }}"
   class="t1-pbox{{ $t1Out ? ' t1-pbox--out' : '' }}"
   target="_blank">
    <article class="t1-pbox__inner">
        <div class="t1-pbox__media">
            @if (!empty($show_product_countdown))
                <ul class="t1-pbox__timer" dir="ltr" aria-label="زمان باقی‌مانده پیشنهاد">
                    <li>
                        <span id="days{{ $product['id'] }}"></span>
                        <small>روز</small>
                    </li>
                    <li>
                        <span id="hours{{ $product['id'] }}"></span>
                        <small>ساعت</small>
                    </li>
                    <li>
                        <span id="minutes{{ $product['id'] }}"></span>
                        <small>دقیقه</small>
                    </li>
                    <li>
                        <span id="seconds{{ $product['id'] }}"></span>
                        <small>ثانیه</small>
                    </li>
                </ul>
            @endif
            @if ($product->hasVariants())
                <span class="t1-pbox__chip t1-pbox__chip--opt" aria-label="محصول دارای گزینه">
                    <i class="bi bi-sliders" aria-hidden="true"></i>
                </span>
            @endif
            <img src="{{ $product->getImage('medium') }}"
                 class="t1-pbox__img"
                 alt="{{ $product['title'] }}"
                 title="{{ $product['title'] }}"
                 loading="lazy"
                 width="300"
                 height="300">
            <div class="t1-pbox__body">
                <h3 class="t1-pbox__title">{{ $product['title'] }}</h3>
                @if ($t1Priced)
                    <div class="t1-pbox__price">
                        @if ($t1Off)
                            <div class="t1-pbox__compare">
                                <span class="t1-pbox__off">@toPersianNumber($product->percent)٪</span>
                                <del class="t1-pbox__was">@toPersianNumber(number_format($product['price']))</del>
                            </div>
                        @endif
                        <p class="t1-pbox__now">
                            <span class="t1-pbox__amount">@toPersianNumber(number_format($product['final_price']))</span>
                            <span class="t1-pbox__unit">تومان</span>
                        </p>
                    </div>
                @elseif (! $t1Out)
                    <p class="t1-pbox__status">تماس بگیرید</p>
                @else
                    <p class="t1-pbox__status">ناموجود</p>
                @endif
            </div>
        </div>
    </article>
</a>

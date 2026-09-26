<div class="col-xl-8 col-lg-8 ps-0 pe-0 pe-lg-2 mt-4">
    <div class="cart-card cart-card--review">
        <div class="cart-card__head">
            <div>
                <h1 class="cart-card__title">بررسی سفارش</h1>
                <p class="cart-card__meta">{{ count($items->toArray(request())['data']) }} کالا در سبد شما</p>
            </div>
        </div>

        <div class="cart-review-grid">
            @foreach($items->toArray(request())['data'] as $item)
                <article class="cart-review-item">
                    <a href="{{ $item['product_url'] }}" target="_blank" class="cart-review-item__media">
                        <img src="{{ $item['product_image'] }}" alt="{{ $item['product_title'] }}" title="{{ $item['product_title'] }}" loading="lazy">
                    </a>
                    <div class="cart-review-item__body">
                        <a class="cart-review-item__title" target="_blank" href="{{ $item['product_url'] }}">
                            {{ $item['product_title'] }}
                        </a>

                        @if($item['percent'])
                            <div class="cart-review-item__meta">
                                <span class="cart-product__discount font-num-r">{{ @$item['percent'] }}٪ تخفیف</span>
                            </div>
                        @endif

                        @if(@$item['variant_id'])
                            @foreach(@$item['specifications'] as $specification)
                                <div class="cart-review-item__spec">
                                    <span>{{ @$specification->parent->title }}</span>
                                    <span>
                                        @if(@$specification->parent->is_color)
                                            <span class="cart-product__color" style="background-color: {{ @$specification->color_code == null || $specification->color_code == 'undefined' ? '#000000' : $specification->color_code }};"></span>
                                        @endif
                                        {{ @$specification->title }}
                                    </span>
                                </div>
                            @endforeach
                        @endif

                        <div class="cart-review-item__qty font-num-r">
                            تعداد: {{ @$item['quantity'] }} عدد
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>

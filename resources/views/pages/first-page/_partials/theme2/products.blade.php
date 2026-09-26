@if (count($products) > 0)
<section class="products" aria-labelledby="home-products-title" @if(!empty($home_product_tabs)) data-product-tabs @endif>
    <div class="container">
        <div class="products-head" data-reveal>
            <div class="products-head__copy">
                <span class="products-head__eyebrow">منتخب فروشگاه</span>
                <h2 id="home-products-title" class="products-head__title">محصولات {{@$settings['first_page_shop_title']}}</h2>
            </div>
            <a href="{{ route('product.get-all') }}" class="products-head__all">
                همه محصولات
            </a>
        </div>
        @if (!empty($home_product_tabs))
            <div class="products-tabs" role="tablist" aria-label="فیلتر دسته محصولات" data-reveal>
                <button type="button"
                        class="products-tabs__btn is-active"
                        role="tab"
                        id="home-products-tab-all"
                        aria-selected="true"
                        aria-controls="home-products-grid"
                        data-filter="all"
                        tabindex="0">
                    همه
                </button>
                @foreach ($home_product_tabs as $tab)
                    <button type="button"
                            class="products-tabs__btn"
                            role="tab"
                            id="home-products-tab-{{ $tab['id'] }}"
                            aria-selected="false"
                            aria-controls="home-products-grid"
                            data-filter="{{ $tab['id'] }}"
                            tabindex="-1">
                        {{ $tab['title'] }}
                    </button>
                @endforeach
            </div>
        @endif
        <div class="products-grid" id="home-products-grid" role="tabpanel" aria-labelledby="home-products-tab-all" data-reveal-group>
            @foreach ($products as $product)
                <div class="products-grid__item"
                     data-reveal
                     data-root-cats="{{ implode(',', $home_product_root_ids[$product->id] ?? []) }}">
                    @include('layouts.common.product.product-box')
                </div>
            @endforeach
        </div>
        <p class="products-empty" hidden aria-live="polite">محصولی در این دسته نیست.</p>
    </div>
</section>
@endif

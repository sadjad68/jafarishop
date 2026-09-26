@if (count($products) > 0)
    <section class="products t1-section">
        <div class="container">
            @include('pages.first-page._partials.theme1._section-head', [
                't1_eyebrow' => 'محصولات',
                't1_title' => 'محصولات ' . @$settings['first_page_shop_title'],
                't1_center' => true,
            ])
            <div class="products-grid" data-reveal-group>
                @foreach ($products as $product)
                    <div class="products-grid__item" data-reveal>
                        @include('layouts.common.product.product-box')
                    </div>
                @endforeach
            </div>
            <div class="products-cta text-center">
                <a href="{{ route('product.get-all') }}" class="t1-link-arrow h-rotate">
                    مشاهده محصولات
                </a>
            </div>
        </div>
    </section>
@endif

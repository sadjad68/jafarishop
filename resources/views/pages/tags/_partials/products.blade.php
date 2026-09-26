<div class="products">
    <div class="sk-section-head">
        <span class="sk-section-head__eyebrow">فروشگاه</span>
        <h2 class="sk-section-head__title">محصولات {{ $tag['title'] }}</h2>
    </div>
    @if(count($products) > 0)
        <div class="row w-100 m-0 lists">
            @foreach($products as $product)
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 p-sm-2 p-1">
                    @include('layouts.common.product.product-box')
                </div>
            @endforeach
        </div>
        <nav class="plp-pagination" aria-label="صفحه‌بندی محصولات">
            @component("layouts.common.pagination.default")
                @slot("paginator",$products)
            @endcomponent
        </nav>
    @else
        <p class="sk-empty">محصولی با این تگ پیدا نشد.</p>
    @endif
</div>

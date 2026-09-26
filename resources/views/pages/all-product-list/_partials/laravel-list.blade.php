<div class="row w-100 m-0 p-0" id="laravel-base">
    @foreach($products as $product)
        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 p-sm-2 p-1">
            @include('layouts.common.product.product-box')
        </div>
    @endforeach
</div>

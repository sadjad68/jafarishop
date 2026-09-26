<div class="products">
    <div class="title-section mb-4 px-md-2 px-1">
        <p class="fw-bolder h5 mb-1 title">محصولات</p>
        <p class="font-th small op-lighter short-des">
            محصولات یافت شده مرتبط با "{{$search}}"
        </p>
    </div>
    <div class="row w-100 m-0 lists">
        @forelse($result['data'] as $searched_product)
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 p-sm-2 p-1">
        @include('layouts.common.product.product-box',['product' => $searched_product])

        </div>
        @empty
        {{-- //Todo ui : قسمت خالی بودن --}}
        @endforelse
    </div>
    @include('pages.search._partials._pagination')
</div>

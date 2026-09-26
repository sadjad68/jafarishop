<div class="product-brands">
    <div class="title-section mb-4 px-md-2 px-1">
        <p class="fw-bolder h5 mb-1 title">برندها</p>
        <p class="font-th small op-lighter short-des">
            برندهای یافت شده مرتبط با "{{$search}}"
        </p>
    </div>
    <div class="brand-tile-grid">
        @forelse($result['data'] as $searched_brand)
            @include('layouts.common.brand.brand-tile', ['brand' => $searched_brand])
        @empty
            {{--            //Todo ui : قسمت خالی بودن --}}
        @endforelse
    </div>
    @include('pages.search._partials._pagination')
</div>

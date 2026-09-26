<div class="product-category">
    <div class="title-section mb-4 px-md-2 px-1">
        <p class="fw-bolder h5 mb-1 title">دسته بندی محصولات</p>
        <p class="font-th small op-lighter short-des">
            محصولات یافت شده مرتبط با "{{$search}}"
        </p>
    </div>
    <ul class="category-grid" aria-label="دسته بندی محصولات">
        @forelse($result['data'] as $searched_category)
            @include('layouts.common.category.category-card', ['category' => $searched_category])
        @empty
            {{--            //Todo ui : قسمت خالی بودن --}}
        @endforelse
    </ul>
    @include('pages.search._partials._pagination')
</div>

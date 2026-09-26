{{-- @include('pages.product-detail._partials.tags') --}}
@if($product['description'] != null)
    <div class="pdp-tabs__prose description content">
        {!! @$product['description'] !!}
    </div>
@else
    <div class="pdp-tabs__empty description">
        <div class="pdp-tabs__empty-icon col-xxl-2 col-xl-3 col-lg-4 col-md-5 col-sm-6 col-5 p-0 m-auto text-center">
            <img src="{{ asset('assets/site/images/empty-states/description-empty.png') }}" class="w-100" alt="empty-state" title="empty-state" loading="lazy">
        </div>
        <p class="pdp-tabs__empty-text font-md mb-0 mt-3 text-center">توضیحاتی موجود نیست!</p>
    </div>
@endif

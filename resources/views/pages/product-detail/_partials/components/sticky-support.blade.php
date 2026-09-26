@if (@$settings['show_share_button'] == 1 && !empty(@$settings['share_type']))
    <div class="pdp-sticky-bar__social d-lg-none">
        @include('pages.product-detail._partials.components.btn-pm-social')
    </div>
@endif

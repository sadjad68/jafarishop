@push('scripts')
    <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
    <script src="{{asset('assets/admin/js/vue-select.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/admin/css/vue-select.css')}}">
@endpush
<div class="container-fluid">
    <input type="hidden" name="product_id" value="{{@$product->id}}" />
    <div class="card-block row w-100 m-0">
        <div class="bg-light border p-3 rounded-5 mb-3 position-relative variant-main-spec-panel">
                    @include('admin.product.product.variant._partials.main-variant-form')
                </div>

        @include('admin.product.product.variant._partials.specification')

    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        Vue.component("v-select", VueSelect.VueSelect);
    </script>
    @include('admin._layouts.blocks.utils.confirmDelete')
@endpush

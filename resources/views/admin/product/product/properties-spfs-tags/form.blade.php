@push('scripts')
    <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
    <script src="{{asset('assets/admin/js/vue-select.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/admin/css/vue-select.css')}}">
@endpush
<div class="container-fluid">
    <input type="hidden" name="product_id" value="{{@$product->id}}" />
    <div class="card-block row w-100 m-0">
        @include('admin.product.product.properties-spfs-tags._partials.property')
{{--        @include('admin.product.product.properties-spfs-tags._partials.tag')--}}
        @include('admin.product.product.properties-spfs-tags._partials.specification')
        <div class="w-100 pe-0 text-end">
            <button class="btn btn-custom rounded-custom w-fit px-3 py-2" type="submit">
                ذخیره
            </button>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        Vue.component("v-select", VueSelect.VueSelect);
    </script>
    @include('admin._layouts.blocks.utils.confirmDelete')
@endpush

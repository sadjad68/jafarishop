
<div class="">
<input type="hidden" name="product_id" value="{{@$data->id}}">
    <div class="card-block row w-100 m-0">

        <div class="p-0">
            <div class="card-block row w-100 m-0">

                <div class="col-12 p-2" >
                    @include('admin.product.product.image._partials.image')

                </div>
            </div>
        </div>
        <div class="w-100 pe-0">
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
@endpush

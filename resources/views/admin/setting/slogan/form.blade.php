<div class="alert alert-success d-block" role="alert">
شعارها در صفحه جزیيات محصول نمایش داده می شوند.
</div>

<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-4 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="value"
                    label="شعار"
                    :validations="['requiredCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="icon"
                label="آیکون (سایز : w64 * h64 )"
                :imageSrc="(isset($data) && $data->icon) ? $data->image : null"
                :validations="['requiredCms']"

            />
        </div>
        <div class="col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="active"
                    label="نمایش "
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/admin/css/233bootstrap-select.min.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('assets/admin/js/833bootstrap-select.min.js')}}"></script>
@endpush

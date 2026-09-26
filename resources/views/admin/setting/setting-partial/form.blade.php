<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-4 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="title"
                    label="عنوان"
                    :validations="['requiredCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 col-12 p-2">
            <x-cms-select
                name="parent_id"
                label="دسته بندی"
                :options="$partials"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :selectedOption="isset($data->parent) ? $data->parent->id : null"
            />
        </div>
        <div class="col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="show"
                    label="نمایش "
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="w-100 pe-0">
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

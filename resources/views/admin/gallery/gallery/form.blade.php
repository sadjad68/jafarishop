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
                :options="$categories"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :selectedOption="isset($data->category) ? $data->category->id : null"
            />
        </div>
        <div class="col-xxl-4 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="file"
                label="تصویر (سایز : w400 * h581 )"
                :imageSrc="(isset($data) && $data->file) ? $data->item_image : null"
                :validations="['requiredCms']"

            />
        </div>
        <div class="col-12 p-2">
            <div class="form-group">
                <div class="form-group">
                    <x-cms-text-area
                        name="description"
                        label="توضیحات "
                        type="text"
                        :valueData="@$data"
                    />
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
        @include('admin.components.show-first-page',['section'=>'gallery'])
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

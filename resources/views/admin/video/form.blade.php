<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        @if($category->isEmpty())
            <div class="col-12 p-2">
                <div class="alert alert-warning mb-0">
                    ابتدا یک دسته‌بندی با نوع «ویدیو» بسازید.
                    <a href="{{ route('admin.blog-category.create') }}">افزودن دسته‌بندی</a>
                </div>
            </div>
        @endif
        <div class="col-xxl-3 col-sm-6 p-2">
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
        <div class="col-xxl-3 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="url"
                    label="آدرس url"
                    :validations="['requiredCms','urlCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="author"
                    label="نام نویسنده"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 p-2">
            <div class="form-group">
                <label>تاریخ انتشار</label>
                <input class="form-control bg-light rounded-custom" type="text" id="datepicker1" name="publish_date" placeholder="تاریخ انتشار" value="@if(isset($data)){{ jdate('d/m/Y', @$data->publish_date) }} @endif">
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 p-2">
            <x-cms-select
                name="parent_id"
                label="دسته بندی"
                :options="$category"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :selectedOption="isset($data->parent_id) ? $data->parent_id : ($category->count() === 1 ? $category->first()->id : null)"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 p-2">
            <x-cms-multi-select
                name="services"
                label="خدمات"
                :options="$services"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :selectedOptions="isset($data) ? $data->services->map(function($item){return $item->id;})->toArray() : []"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 p-2">
            <x-cms-image-input
                name="image"
                label="تصویر (سایز : w450 * h450 )"
                :imageSrc="(isset($data) && $data->image) ? $data->getItemImage() : null"
                :validations="['requiredCms']"
                width="450"
                height="450"
                cropper="1"
            />
        </div>
        <div class="col-12 p-2">
            <div class="form-group">
                <x-cms-ck-editor
                    name="description"
                    label="توضیحات"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="call_to_action"
                    label="نمایش دکمه تماس با کارشناسان"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            @include('admin.components.show-first-page',['section'=>'blog'])
        </div>
        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2" @if($category->isEmpty()) disabled @endif>
                ذخیره
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{asset('assets/admin/js/bootstrap-datepicker.min.js?v0.01')}}"></script>
    <script src="{{asset('assets/admin/js/bootstrap-datepicker.fa.min.js?v0.01')}}"></script>
    <script>
        $(document).ready(function() {
            $("#datepicker1").datepicker({
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endpush

<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
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
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
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
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-select
                name="parent_id"
                label="خدمت والد"
                :options="$services"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :clearable="true"
                :selectedOption="isset($data->parent_id) ? $data->parent_id : null"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="image"
                label="تصویر (سایز : w935 * h500 )"
                :imageSrc="(isset($data) && $data->image) ? $data->image : null"
                :deletable="true"
                :deleteUrl="'model='.\App\Modules\Service\Entities\Service::class.'&id='.@$data['id']"
                width="935"
                height="500"
                 cropper="1"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="header_image"
                label="تصویر هدر (سایز : w1920 * h900 )"
                :imageSrc="(isset($data) && $data->header_image) ? $data->header_image : null"
                :deletable="true"
                :deleteUrl="'model='.\App\Modules\Service\Entities\Service::class.'&id='.@$data['id']"
                width="1920"
                height="900"
                cropper="1"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="phone_number"
                    label="شماره کارشناس مربوطه"
                    :validations="['numberCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-select
                name="description_position"
                label="جایگاه نمایش توضیحات"
                :options="$description_positions"
                optionValue="value"
                optionLabel="title"
                :searchable="false"
                :clearable="false"
                :selectedOption="isset($data->description_position) ? $data->description_position : 'top'"
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
        <div class="col-12 p-2">
            <div class="form-group">
                <div class="form-group">
                    <x-cms-text-area
                        name="short_description"
                        label="توضیحات کوتاه"
                        type="text"
                        :valueData="@$data"
                    />
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
        @include('admin.components.show-first-page',['section'=>'service'])
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="show_in_menu"
                    label="نمایش در منو "
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="show_in_footer"
                    label="نمایش در فوتر "
                    :valueData="@$data"
                />
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-sm-6 col-12 ms-auto p-2">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>

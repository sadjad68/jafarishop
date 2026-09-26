@php
    $themeProvider = app(\App\Modules\General\Helper\ThemeProvider::class);
 @endphp
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
        @if($themeProvider->hasSection('adminSections','highlight'))
            <div class="col-xxl-3 col-sm-6 col-12 p-2">
                <div class="form-group">
                    <x-cms-input
                        name="link"
                        label="لینک"
                        :validations="[]"
                        type="text"
                        :valueData="@$data"
                    />
                </div>
            </div>
        @endif
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="image"
                label="تصویر (سایز : w{{$themeProvider->getSliderSizes()['desktop']['width']}} * h{{$themeProvider->getSliderSizes()['desktop']['height']}} )"
                :imageSrc="(isset($data) && $data->image) ? $data->image : null"
                :validations="['requiredCms']"
                width="{{$themeProvider->getSliderSizes()['desktop']['width']}}"
                height="{{$themeProvider->getSliderSizes()['desktop']['height']}}"
                 cropper="1"
            />
        </div>
        <div class="col-xxl-3 col-sm-6 col-12 p-2">
            <x-cms-image-input
                name="image_mobile"
                label="تصویر موبایل (سایز : w{{$themeProvider->getSliderSizes()['mobile']['width']}} * h{{$themeProvider->getSliderSizes()['mobile']['height']}} )"
                :imageSrc="(isset($data) && $data->image_mobile) ? $data->image_mobile : null"
                :validations="['requiredCms']"
                width="{{$themeProvider->getSliderSizes()['mobile']['width']}}"
                height="{{$themeProvider->getSliderSizes()['mobile']['height']}}"
                 cropper="1"
            />
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
        @include('admin.components.show-first-page',['section'=>'banner'])
        </div>
        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
    </div>
</div>

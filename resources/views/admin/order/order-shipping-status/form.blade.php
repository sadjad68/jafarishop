<div class="container-fluid">
    <div class="card-block row w-100 m-0">
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
                <label for="">
                    رنگ
                    <span class="text-danger">*</span>
                </label>
                <input requiredCms type="color" class="form-control bg-light rounded-custom" name="color" placeholder=""
                       value="{{@$data['color'] ? @$data['color'] : null}}" >
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2 mt-3">
            <div class="form-group">
                <x-cms-check-box
                    name="default"
                    label="پیش فرض "
                    :valueData="@$data"
                    :value="0"
                />
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12 p-2 mt-3">
            <div class="form-group">
                <x-cms-check-box
                    name="sending_sms"
                    label="به همراه ارسال پیامک"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2 mt-3">
            <div class="form-group">
                <x-cms-check-box
                    name="for_send"
                    label="آماده ارسال "
                    :valueData="@$data"
                    :value="0"
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



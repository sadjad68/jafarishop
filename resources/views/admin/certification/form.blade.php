
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
                        <x-cms-image-input
                            name="image"
                            label="تصویر (سایز : w700 * h700 )"
                            :imageSrc="(isset($data) && $data->image) ? $data->getImage() : null"
                            :validations="['requiredCms']"
                            width="700"
                            height="700"
                             cropper="1"
                        />
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                    @include('admin.components.show-first-page',['section'=>'certification'])
                    </div>
                    <div class="w-100 pe-2">
                        @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                            ذخیره
                        </button>
                    </div>
                </div>
            </div>


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
                                name="map"
                                label="لینک گوگل مپ"
                                type="text"
                                :valueData="@$data"
                            />
                        </div>
                    </div>
                    <div class="col-12 p-2">
                        <div class="form-group">
                            <div class="form-group">
                                <x-cms-text-area
                                    name="address"
                                    label="آدرس "
                                    type="text"
                                    :validations="['requiredCms']"
                                    :valueData="@$data"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 p-2">
                        <div class="form-group">
                            <x-cms-check-box
                                name="main"
                                label="شعبه اصلی "
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

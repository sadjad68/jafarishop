
            <div class="container-fluid">
                <div class="card-block row w-100 m-0">
                    <div class="col-xxl-4 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <x-cms-input
                                name="old_address"
                                label="آدرس قدیم"
                                :validations="['requiredCms']"
                                type="text"
                                :valueData="@$data"
                            />
                        </div>
                    </div>
                    <div class="col-xxl-4 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <x-cms-input
                                name="new_address"
                                label="آدرس جدید"
                                :validations="['requiredCms']"
                                type="text"
                                :valueData="@$data"
                            />
                        </div>
                    </div>
                    <div class="col-xxl-4 col-sm-6 col-12 p-2">
                        <div class="form-group">
                            <label>
                            نوع
                            </label>
                            <select name="type" class="w-100 form-select bg-light rounded-custom " >
                                <option value="301">301</option>
                                <option value="302">302</option>

                            </select>
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

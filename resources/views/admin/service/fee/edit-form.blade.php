

            <div class="">
                <div class="card-block row w-100 m-0">
                    <div class="col p-2">
                        <div class="form-group">
                            <x-cms-input
                                name="minimum_price"
                                label="(تومان)حداقل قیمت"
                                :validations="['requiredCms','numberCms']"
                                type="text"
                                :valueData="@$data"
                            />
                        </div>
                    </div>
                    <div class="col p-2">
                        <div class="form-group">
                            <x-cms-input
                                name="maximum_price"
                                label="حداکثر قیمت(تومان)"
                                :validations="['requiredCms','numberCms']"
                                type="text"
                                :valueData="@$data"
                            />
                        </div>
                    </div>
                        <div class="col p-2">
                            <x-cms-select
                                name="service_id"
                                label="خدمات"
                                :options="$services"
                                optionValue="id"
                                optionLabel="title"
                                :validations="['requiredCms']"
                                :searchable="true"
                                :selectedOption="isset($data->service_id) ? $data->service_id : null"
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
                    <div class="w-100 pe-0">
                        <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                            ذخیره
                        </button>
                    </div>
                </div>
            </div>

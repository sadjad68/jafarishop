<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        @if(@$data->bank_type === 'cardtocard')
            <div class="col-12 p-2">
                <div class="alert alert-danger mb-0" role="alert">
                    <strong>توجه:</strong>
                    اگر فیلد «مهلت تایید فیش و نگهداری رزرو موجودی (دقیقه)» را پر کنید، پس از پایان این مهلت
                    و در صورت عدم تایید فیش، رزرو موجودی آزاد و سفارش منقضی می‌شود.
                    در صورت خالی گذاشتن این فیلد، برای رزرو موجودی هیچ زمان انقضایی در نظر گرفته نمی‌شود.
                </div>
            </div>
        @endif
        @foreach($bank_fields as $key => $bank_field)
        @php
            $configValue = data_get(json_decode(data_get($data, 'config') ?: '{}', true) ?: [], $key);
        @endphp
        <div class="col-xxl-3 col-sm-6 p-2 @if(($bank_field['type'] ?? '') === 'checkbox') mt-3 @endif">
            <div class="form-group">
                @if(($bank_field['type'] ?? '') === 'options')
                    <label for="bank_config_{{ $key }}">
                        {{ @$bank_field['value'] }}
                    </label>
                    <select
                        id="bank_config_{{ $key }}"
                        class="w-100 form-control bg-light rounded-custom"
                        name="{{ $key }}"
                    >
                        @foreach($bank_field['values'] as $optionLabel => $optionValue)
                            <option
                                value="{{ $optionValue }}"
                                @selected((string) $configValue === (string) $optionValue)
                            >
                                {{ $optionLabel }}
                            </option>
                        @endforeach
                    </select>
                @elseif(($bank_field['type'] ?? '') === 'checkbox')
                    <div class="form-check border border-custom2 w-fit rounded-custom p-0 d-flex align-items-center">
                        <input
                            class="form-check-input my-2 ms-2"
                            style="width: 30px; height: 30px;"
                            id="bank_config_{{ $key }}"
                            name="{{ $key }}"
                            type="checkbox"
                            role="switch"
                            value="1"
                            @checked((int) $configValue === 1)
                        >
                        <label class="form-check-label p-2" for="bank_config_{{ $key }}">
                            {{ @$bank_field['value'] }}
                        </label>
                    </div>
                @else
                    <label for="bank_config_{{ $key }}">
                        {{ @$bank_field['value'] }}
                        @if($key !== 'reservation_expire_minutes')
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input
                        @if($key !== 'reservation_expire_minutes') requiredCms @endif
                        id="bank_config_{{ $key }}"
                        type="{{ ($bank_field['type'] ?? '') === 'string' ? 'text' : $bank_field['type'] }}"
                        class="form-control bg-light rounded-custom"
                        name="{{ $key }}"
                        placeholder="{{ $key === 'reservation_expire_minutes' ? 'خالی = بدون انقضا' : '' }}"
                        value="{{ $configValue }}"
                    >
                @endif
            </div>
        </div>
        @endforeach

        <div class="col-xxl-3 col-sm-6 p-2">
            <div class="form-group">
                <label for="gateway_tariff">
                    تعرفه درگاه (درصد)
                </label>
                <input
                    numberCms
                    maxCms="100"
                    type="text"
                    id="gateway_tariff"
                    class="form-control bg-light rounded-custom"
                    name="gateway_tariff"
                    placeholder="0"
                    value="{{ old('gateway_tariff', @$data->gateway_tariff ?? 0) }}"
                >
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12 p-2 mt-3">
            <div class="form-group">
                <x-cms-check-box
                    name="status"
                    label="نمایش "
                    :valueData="@$data"
                />
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12 p-2 mt-3">
            <div class="form-group">
                <x-cms-check-box
                    name="admin_test"
                    label="درگاه تستی فقط برای ادمین ها"
                    :valueData="@$data"
                />
            </div>
            <span>مناسب برای زمانی که میخواهید درگاه رو تست کنید و کاربران امکان استفاده ازین درگاه را ندارند.</span>
        </div>

        <div class="w-100 pe-0">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
                ذخیره
            </button>
        </div>
        </div>
    </div>



<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-3 col-sm-3 col-12 p-2">
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
            <label for="type">
                نوع
            </label>
            <select
                id="type"
                class="w-100 form-control"
                placeholder=" نوع را انتخاب کنید"
                name="type"
                v-model="selectedType"
            >
                <option value="">
                    انتخاب کنید
                </option>
{{--                <option value="chapar">--}}
{{--                    چاپار--}}
{{--                </option>--}}
                <option value="with_weight">
                    بر اساس وزن
                </option>
                <option value="fixed_price">
                  با قیمت ثابت
                </option>
            </select>
        </div>
        <div class="col-xxl-3 col-sm-3 col-12 p-2" v-if="selectedType == 'with_weight' ||  selectedType == 'fixed_price'">
            <div class="form-group">
                <x-cms-input
                    name="price"
                    label="قیمت (تومان) "
                    :validations="['requiredCms','numberCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-3 col-12 p-2">
            <div class="form-group">
                <x-cms-input
                    name="price_ceiling"
                    label="سقف قیمت برای رایگان شدن (تومان) "
                    :validations="['numberCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-3 col-sm-3 col-12 p-2" v-if="selectedType == 'chapar'">
            <button type="button"
                    class="btn me-2 p-0 bg-transparent border-0 mt-4 mb-3 fs-6 shadow-none d-flex align-items-center gap-2 text-info "
                    data-bs-toggle="modal"
                    data-bs-target="#chaparModal"
                    data-bs-title="راهنمای چاپار" title="راهنمای چاپار">
                <i class="bi bi-info-square d-flex"></i>
                راهنمای چاپار
            </button>
        </div>
        @include('admin.order.shipping-method.chapar-modal')


        <div role="alert" class="alert alert-info d-block" v-if="selectedType == 'with_weight'">
        توجه داشته باشید قیمت انتخابی شما به ازای هر ۱۰۰ گرم محصول می باشد، همچنین در این پروسه قیمت بر اساس وزن گرد شده به سمت بالا تعیین می شود
        </div>

            @foreach($methods as $key => $method)
            <div class="row w-100 m-0" v-if="selectedType == '{{$key}}'">
                @foreach($method as $key_method => $row)
{{--                    @foreach($row['values'] as $key2 => $type)--}}
{{--                        @dd($key2,$type)--}}
{{--                    @endforeach--}}
                <div class="col-xxl-3 col-sm-6 p-2">
                    <div class="form-group">
                        <label for="">
                            {{@$row['value']}}
                        </label>
                        @if($row['type'] == "options")
                            <select   class="w-100 form-control" name="{{$key_method}}" data-live-search="true"  placeholder="انتخاب کنید">
                                @foreach($row['values'] as $key2 => $type)
                                    <option value="{{$type}}"
                                           {{@$data[$key_method] == $type ? 'selected' : '' }}
                         >
                                        {{$key2}}
                                    </option>
                                @endforeach

                            </select>
                        @else
                            <input requiredCms type="{{$row['type']}}" class="form-control bg-light rounded-custom" name="{{$key_method}}" placeholder=""
                                   value="{{@$data['config'] ? json_decode(@$data['config'],true)[$key_method] : null}}" >
                        @endif

                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

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
        <div class="col-xxl-6 col-sm-4 col-12 p-2">
            <div class="border p-1">
                <input
                    type="text"
                    class="form-control mb-2"
                    v-model="searchQuery"
                    placeholder="جستجو .."
                />
                <div class="sd-checkbox">
                    <ul class="p-0 m-0" style="list-style-type: none">
                        <li v-for="city in filteredCities" :key="city.id">
                            <label class="custom-ch">
                                @{{ city.name }}
                                <input
                                    type="checkbox"
                                    :value="city.id"
                                    v-model="city.checked"
                                    name="cities[]"
                                    class="form-control"
                                    multiple
                                />
                                <span class="checkmark"></span>
                            </label>
                        </li>
                    </ul>
                </div>
                <button type="button" @click="selectAll" class="btn btn-space btn-info m-0 px-5">انتخاب همه</button>
                <button type="button" @click="deselectAll" class="btn btn-space btn-danger m-0 px-5">لغو انتخاب همه</button>
            </div>
        </div>
        <div class="col-xxl-2 col-sm-2 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="status"
                    label="نمایش "
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-2 col-sm-2 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="freight_balance"
                    label=" پس کرایه"
                    :valueData="@$data"
                    :value="0"
                />
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                اطلاعات فرستنده
            </div>
            <div class="card-body row">
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_name"
                            label="نام فرستنده"
                            :validations="['requiredCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_company"
                            label="شرکت فرستنده"
                            :validations="['requiredCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_phone"
                            label="شماره تلفن فرستنده"
                            :validations="['requiredCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_mobile"
                            label="شماره همراه فرستنده"
                            :validations="['requiredCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_email"
                            label="ایمیل فرستنده"
                            :validations="['requiredCms']"
                            type="email"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2 position-relative select-vue">
                    <x-cms-select
                        name="sender_city_id"
                        label="شهر فرستنده"
                        :options="$cities"
                        optionValue="id"
                        :validations="['requiredCms']"
                        optionLabel="name"
                        :searchable="true"
                        :selectedOption="isset($data->sender_city_id) ? $data->sender_city_id : null"
                    />
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_address"
                            label="آدرس فرستنده"
                            :validations="['requiredCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-3 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="sender_postal_code"
                            label="کد پستی فرستنده"
                            :validations="['requiredCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>

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
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/admin/css/233bootstrap-select.min.css')}}">
    <style>
        .select-vue .dropdown-menu{
            text-align: right;
            width: 100%;
            z-index: 1200;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('assets/admin/js/833bootstrap-select.min.js')}}"></script>
    <script type="text/javascript">
        function selects(){
            var ele=document.getElementsByName('cities[]');
            for(var i=0; i<ele.length; i++){
                if(ele[i].type=='checkbox')
                    ele[i].checked=true;
            }
        }
        function deSelect(){
            var ele=document.getElementsByName('cities[]');
            for(var i=0; i<ele.length; i++){
                if(ele[i].type=='checkbox')
                    ele[i].checked=false;

            }
        }

        function myFunction() {

            var input, filter, ul, li, la, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");


            for (i = 0; i < li.length; i++) {
                la = li[i].getElementsByTagName("label")[0];
                txtValue = la.textContent || la.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
@endpush
@push('scripts')
    <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vue-select.css') }}">
    <script type="text/javascript">
        new Vue({
            el: "#cms-form-shipping",
            data: {
                selectedType: '{{@$data->type}}',
                searchQuery: "",
                cities: @json($cities)
            },
            computed: {
                filteredValues() {
                    const query = this.searchQuery.toLowerCase();
                    return this.values.filter(value => value.title.toLowerCase().includes(query));
                },
                filteredCities() {
                    return this.cities.filter(city =>
                        city.name.toUpperCase().includes(this.searchQuery.toUpperCase())
                    );
                }
            },
            methods: {
                errorsGenerator(inputs) {
                    const inputErrors = [];
                    inputs.forEach(element => {
                        const errors = [];
                        const isEmpty = element.value.trim() === '';
                        if (element.hasAttribute("requiredCms") && isEmpty) {
                            errors.push({ message: "وارد کردن مقدار الزامیست", validation: "requiredCms" });
                        }
                        if (errors.length > 0) {
                            inputErrors.push({ element: element, errors: errors });
                        }
                    });
                    return inputErrors;
                },
                resetErrorElements(inputs) {
                    inputs.forEach(element => {
                        element.style.border = '';
                        const previousError = element.nextSibling;
                        if (previousError && previousError.tagName === 'P') {
                            previousError.parentNode.removeChild(previousError);
                        }
                    });
                    return true;
                },
                clearError(element) {
                    element.style.border = '';
                    const previousError = element.nextSibling;
                    if (previousError && previousError.tagName === 'P') {
                        previousError.parentNode.removeChild(previousError);
                    }
                },
                addListenerInput(element) {
                    element.addEventListener('input', () => this.clearError(element));
                    element.addEventListener('change', () => this.clearError(element));
                    return true;
                },
                validateForm(submitEvent) {
                    const inputs = submitEvent.target.querySelectorAll('input, select, textarea');
                    this.resetErrorElements(inputs);
                    const errors = this.errorsGenerator(inputs);
                    if (errors.length > 0) {
                        errors.forEach(error => {
                            const errorElement = document.createElement('p');
                            errorElement.textContent = error.errors[0].message;
                            errorElement.style.color = "red";
                            error.element.style.border = '1px solid red';
                            error.element.parentNode.insertBefore(errorElement, error.element.nextSibling);
                            this.addListenerInput(error.element);
                        });
                        Swal.fire({
                            icon: 'error',
                            text: "کاربر گرامی اطلاعات فرم را به درستی پر کنید",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        return false;
                    } else {
                        submitEvent.target.submit();
                    }
                },
                getCities() {
                    return [
                        // مقداردهی اولیه با داده‌های سرور
                        ...window.cities.map(city => ({
                            id: city.id,
                            name: city.name,
                            checked: city.checked || false
                        }))
                    ];
                },
                selectAll() {
                    this.cities.forEach(city => city.checked = true);
                },
                deselectAll() {
                    this.cities.forEach(city => city.checked = false);
                }
            },
            async mounted() {
                // await this.getVideos();
            }
        });

    </script>
    @include('admin._layouts.blocks.utils.confirmDelete')

@endpush

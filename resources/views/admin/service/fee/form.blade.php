<div class="container-fluid">

    <div class="card-block row w-100 m-0">
        <div class="position-absolute" style="left: 20px; top: -20px">
            <a @click="plusMe()" class="btn btn-default btn-outline-success">
                <span class="fa fa-plus">اضافه کردن نرخ بعدی</span>
            </a>
        </div>
        <div class="" v-for="rate in rates" :key="rate.id">
            <div class="card-block row w-100 m-0">
                <div class="col-xxl-3 col-sm-6 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="minimum_price[]"
                            label="(تومان)حداقل قیمت"
                            :validations="['requiredCms','numberCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="maximum_price[]"
                            label="حداکثر قیمت(تومان)"
                            :validations="['requiredCms','numberCms']"
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
             <div class="col-xxl-3 col-sm-6 col-12 p-2 d-flex align-items-end">
                    <div class="w-100" id="app">
                        <v-select
                            :id="'select-' + rate.id"
                            :loading="options.length <= 0"
                            v-model="rate.selectedService"
                            :options="options"
                            :validations="['requiredCms']"
                            :reduce="options => options.id"
                            key="id"
                            label="name"
                            @input="onInput"
                            placeholder="انتخاب خدمات"
                        >
                            <template #spinner="{ loading }">
                                <div v-if="loading" class="vs__spinner"/>
                            </template>
                        </v-select>
                    </div>

                </div>
                <input type="hidden" name="service_id[]" :value="rate.selectedService">
            </div>
            <div class="col-12 p-2">
                <div class="form-group">
                    <div class="form-group">
                        <x-cms-text-area
                            name="description[]"
                            label="توضیحات "
                            type="text"
                            :valueData="@$data"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 pe-3">
        <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-fit px-3 py-2">
            ذخیره
        </button>
    </div>
</div>

@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/833bootstrap-select.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vue-select.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/admin/css/vue-select.css')}}">

    <script type="text/javascript">
        Vue.component("v-select", VueSelect.VueSelect);
        var app = new Vue({
            el: '#cms-form-fee',
            data: {
                rates: [
                    {
                        id: 1,
                        selectedService: @if(isset($service)) {{$service->id}} @else null @endif,
                    }
                ],
                options: [
                        @foreach($services as $service)
                    { name: "{{@$service->title}}", id: {{@$service->id}}}@if(!$loop->last), @endif
                    @endforeach
                ]
            },
            methods: {
                plusMe: function () {
                    this.rates.push({
                        id: this.rates.length + 1,
                        selectedService: null,
                    });
                },
                resetErrorElements(inputs) {
                    inputs.forEach(element => {
                        element.style.border = '';
                        const previousError = element.nextSibling;
                        if (previousError && previousError.tagName === 'P') {
                            previousError.parentNode.removeChild(previousError);
                        }
                    })
                    return true;
                },
                errorsGenerator(inputs) {
                    const inputErrors = [];
                    inputs.forEach(element => {
                        const errors = [];
                        const isEmpty = element.value.trim() === '';

                        if (element.hasAttribute("requiredCms")) {
                            if (isEmpty) {
                                errors.push({message: "وارد کردن مقدار الزامیست", validation: "requiredCms"});
                            }
                        }



                        if (element.hasAttribute("numberCms")) {
                            var numberPattern = /^[\d۰۱۲۳۴۵۶۷۸۹]+$/;
                            if (!isEmpty && !numberPattern.test(element.value)) {
                                errors.push({message: "فقط عدد وارد کنید", validation: "numberCms"});
                            }
                        }

                        if (errors.length > 0) {
                            inputErrors.push({element: element, errors: errors});
                        }
                    });
                    return inputErrors;
                },
                clearError(element) {
                    console.log(element);
                    element.style.border = '';
                    const previousError = element.nextSibling;
                    if (previousError && previousError.tagName === 'P') {
                        previousError.parentNode.removeChild(previousError);
                    }
                },
                addListenerInput(element) {
                    const vm = this;
                    element.addEventListener('input', function () {
                        vm.clearError(element);
                    });
                    element.addEventListener('change', function () {
                        vm.clearError(element);
                    });
                    return true;
                },
                validateForm(submitEvent) {
                    const inputs = submitEvent.target.querySelectorAll('input,select,textarea');
                    this.resetErrorElements(inputs);
                    const errors = this.errorsGenerator(inputs);
                    const selectedServiceErrors = this.rates.filter(rate => !rate.selectedService).length > 0;

                    if (errors.length > 0 || selectedServiceErrors) {
                        errors.forEach(error => {
                            const errorElement = document.createElement('p');
                            errorElement.textContent = error.errors[0].message;
                            errorElement.style.color = "red";
                            errorElement.style.display = 'none';
                            error.element.style.border = '1px solid red';
                            errorElement.style.display = 'block';
                            error.element.parentNode.insertBefore(errorElement, error.element.nextSibling);
                            this.addListenerInput(error.element);
                        })
                        if (this.rates.some(rate => !rate.selectedService)) {
                            this.rates.forEach(rate => {
                                const selectElement = document.getElementById(`select-${rate.id}`);
                                selectElement.style.border = '';
                                const previousError = selectElement.nextSibling;
                                if (previousError && previousError.tagName === 'P') {
                                    previousError.parentNode.removeChild(previousError);
                                }
                                const errorElement = document.createElement('p');

                                errorElement.textContent = "وارد کردن مقدار الزامیست";
                                errorElement.style.color = "red";
                                errorElement.style.display = 'none';
                                selectElement.style.border = '1px solid red';
                                selectElement.style.borderRadius="10px";
                                errorElement.style.display = 'block';
                                selectElement.parentNode.insertBefore(errorElement, selectElement.nextSibling);
                                this.addListenerInput(selectElement);
                            });
                        }

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
                sanitizeValueNumber(value) {
                    const validCharacters = /[۰-۹0-9]/g;
                    return value.match(validCharacters)?.join('') || '';
                },
                save: function () {

                }
            },
            mounted() {
                let vm = this;
                let numberInputs = document.querySelectorAll("[numberCms]");
                numberInputs.forEach(item => {
                    item.addEventListener('input', function (event){
                        event.target.value = vm.sanitizeValueNumber(event.target.value);
                    });
                });
            }
        })
    </script>
@endpush

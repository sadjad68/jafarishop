<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-4 col-sm-6 p-2">
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
        <div class="col-xxl-4 col-sm-6 p-2">
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
            <option value="text">
                نوشتاری
            </option>
            <option value="select">
              انتخابی
            </option>
        </select>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
        <label for="type">
        دسته بندی
        </label>
            <v-select multiple  v-model="selectedCategories" :options="categories"
                      :reduce="category => category.id" key="id" label="title" name="categories"  placeholder="انتخاب کنید">
                <template #spinner="{ loading }">
                    <div v-if="loading" class="vs__spinner"/>
                </template>
            </v-select>
            <input type="hidden" name="categories[]" v-for="item in selectedCategories" :value="item">

        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2" v-if="selectedType == 'select'">
            <div class="form-group">
                <x-cms-check-box
                    name="is_filter"
                    label=" نمایش در فیلتر "
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2" v-if="selectedType == 'select'">
            <div class="form-group">
                <x-cms-check-box
                    name="is_color"
                    label="دارای پالت رنگی "
                    :valueData="@$data"
                />
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="active"
                    label="نمایش "
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
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/vue-select.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/admin/css/vue-select.css')}}">
    <script type="text/javascript">
        Vue.component("v-select", VueSelect.VueSelect);

        new Vue({
            el: "#cms-form-specification",
            data: {
                selectedType: '{{@$data->type}}',

                categories: @json($categories),
                selectedCategories: @if(isset($data)) @json($data->categories()->pluck('id')->toArray()) @else [] @endif

            },
            methods: {
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

                        if (errors.length > 0) {
                            inputErrors.push({element: element, errors: errors});
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
                    })
                    return true;
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
                    if (errors.length > 0) {
                        console.log(errors);
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
                onInput(selectedOptions) {

                }

            },
            mounted() {

            }
        });
    </script>
@endpush


<div class="container-fluid">
    <div class="">
        <div class="col-12 p-1">
            <div class="position-relative mb-2 border overflow-hidden rounded-5 row" style="height: 50px;">
                <input type="text" v-model="valueTitle" class="form-control form-control-sm rounded-5 border-0"
                       style="height: 100%;@if($specification->is_color == 1) width:40% @else width:80% @endif"
                       placeholder="جهت افزودن مقدار جدید اینجا تایپ کنید..">
                @if($specification->is_color == 1)
                    <input style="height: 100%;width:40%" type="color" v-model="valueColor"
                           class="form-control form-control-sm rounded-5 border-0"
                           placeholder="انتخاب رنگ">
                @endif
                <button @click="saveValue()"
                        class="btn btn-success btn-sm p-2 rounded-0 border-0 shadow-none position-absolute top-0 bottom-0 end-0"
                        type="button" style="width: 20%;">
                   <span>
                        <i class="bi bi-download"></i>
                       ذخیره
                   </span>
                </button>
            </div>
        </div>

    </div>

    <div class="bg-light p-3">
        <div class="col-12 p-1">
            <input type="text" v-model="searchQuery" class="form-control form-control-sm rounded-5 mb-2"
                   id="searchInput1" placeholder="جستجو در مقادیر...">
        </div>
        <div class="sd-checkbox p-1" v-if="loading == false">
            <ul id="items1" class="p-0 m-0 d-flex align-items-center flex-wrap gap-3" style="list-style-type:none">
                <li v-for="value in filteredValues" :key="value.id">
                    <label class="custom-ch d-flex align-items-center">
                        <span v-if="editingValue && editingValue.id === value.id">
                            <input type="text" v-model="editingValue.title"
                                   class="form-control form-control-sm me-2 var">
                            @if($specification->is_color == 1)
                                <input type="color" v-model="editingValue.color_code"
                                       class="form-control form-control-sm me-2 var"
                                       style="height: 15px !important;"
                                       placeholder="انتخاب رنگ">
                            @endif
                        </span>
                        <span v-else>
                             @if($specification->is_color == 1)
                                 <i class="bi bi-circle-fill" :style="'color: '+value.color_code"></i>
                             @endif
                            @{{ value.title }}
                        </span>
                        <a class="d-flex ms-3 align-items-center" data-bs-toggle="tooltip" data-bs-title="ذخیره"
                           v-if="editingValue && editingValue.id === value.id"
                           @click.prevent="updateValue()"
                           href="#">
                            <i class="d-flex bi bi-save2 color-custom2 fs-6"></i>
                        </a>
                        <a class="d-flex ms-5 align-items-center" data-bs-toggle="tooltip" data-bs-title="حذف"
                           @click="confirmDelete('{{ route('admin.specification-value.delete', ['id' => '__ID__']) }}'.replace('__ID__', value.id))"
                           href="#">
                            <i class="d-flex bi bi-trash3 color-custom2 fs-6"></i>
                        </a>
                        <a class="d-flex ms-3 align-items-center" data-bs-toggle="tooltip" data-bs-title="ویرایش"
                           @click.prevent="editValue(value)"
                           href="#">
                            <i class="d-flex bi bi-pencil-square color-custom2 fs-6"></i>
                        </a>
                    </label>
                </li>
            </ul>
        </div>
        <div class="sd-checkbox p-1" v-else>
            در حال دریافت اطلاعات
            <div class="spinner-border" role="status" style="width:15px;height:15px">
            </div>
        </div>
    </div>

    <div class="w-100 pe-0 text-end">
       @component("admin.components.back-button")
                        @endcomponent
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vue-select.css') }}">
    <script type="text/javascript">
        new Vue({
            el: "#cms-form-specification-value",
            data: {
                values: @json($specification->children),
                valueTitle: '',
                valueColor: '',
                searchQuery: '',
                editingValue: null,
                loading: false,
            },
            computed: {
                filteredValues() {
                    const query = this.searchQuery.toLowerCase();
                    return this.values.filter(value => value.title.toLowerCase().includes(query));
                }
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
                async getValues() {
                    this.loading = true;
                    const response = await axios.get('{{ route('admin.specification-value.values') }}?id=' + {{ $specification->id }});
                    this.values = response.data.values;
                    this.loading = false;
                },
                async saveValue() {
                    if (!(this.valueTitle.length > 0)) {
                        Swal.fire({
                            icon: 'error',
                            text: "عنوان مقدار الزامیست",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        return;
                    }
                    try {
                        let formData = new FormData();
                        formData.append("title", this.valueTitle);
                        formData.append("color_code", this.valueColor);
                        formData.append("parent_id", {{ $specification->id }});
                        this.values.push({title: this.valueTitle, parent_id: "{{ $specification->id }}"});
                        this.valueTitle = '';
                        this.valueColor = '';
                        this.loading = true;
                        await axios.post('{{ route('admin.specification-value.save-values') }}', formData);
                        Swal.fire({
                            icon: 'success',
                            text: "با موفقیت ذخیره شد",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        await this.getValues();
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            text: "با خطا مواجه شدید",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                    this.valueTitle = '';
                },

                editValue(value) {
                    this.editingValue = Object.assign({}, value); // یک کپی از مقدار برای ویرایش
                },
                async updateValue() {
                    try {
                        let formData = new FormData();
                        formData.append("title", this.editingValue.title);
                        formData.append("color_code", this.editingValue.color_code);
                        formData.append("id", this.editingValue.id);
                        const response = await axios.post('{{ route('admin.specification-value.update-values') }}', formData);
                        Swal.fire({
                            icon: 'success',
                            text: "با موفقیت به‌روزرسانی شد",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        this.getValues();
                        this.editingValue = null; // بازنشانی مقدار در حال ویرایش
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            text: "با خطا مواجه شدید",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                },

                confirmDelete(url) {
                    Swal.fire({
                        icon: 'warning',
                        text: "آیا از حذف آیتم مطمئن هستید؟",
                        showCancelButton: true,
                        confirmButtonText: 'تایید و حذف',
                        cancelButtonText: 'لغو',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = url;
                        }
                    });
                }
            }
        });
    </script>
    @include('admin._layouts.blocks.utils.confirmDelete')

@endpush

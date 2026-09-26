<div class="p-0" v-if="mainVariantSpecificationId.length != 0">
    <div class="bg-light border p-3 rounded-5 mb-3 position-relative">
        <h4 class="p-0" style="font-weight: bold;">
            موجودی و قیمت متغییر ها
        </h4>
        @if($product->price_formula != null)
            <div role="alert" class="alert alert-warning d-block">
                توجه داشته باشید محاسبه قیمت این محصول براساس <b> فرمول قیمت </b>خواهد بود
                و قیمت هایی که برای متغییرها ثبت میکنید بی تاثیر خواهد بود و <b> براساس وزن و فرمول قیمت</b> تغییر خواهند کرد.
            </div>
        @endif
        <button type="button"
                @click="addVariant"
                class="btn btn-lg btn-outline-success rounded-custom btn-sm btn-add-form d-flex align-items-center"
        >
            <i class="bi bi-plus d-flex my-0"></i>
            افزودن
        </button>
        <hr>
        <div class="bg-light p-1">
            <div role="alert" class="alert alert-info d-block">
                توجه داشته باشید اگر موجودی محصول برابر با صفر تعیین شده باشد، عبارت <b>ناموجود</b> نمایش داده خواهد شد
                اما اگر محصول موجودی داشته باشد و قیمت آن صفر باشد، در بخش‌های مختلف سایت با عبارت <b>تماس بگیرید</b> نمایش داده می‌شود.
            </div>
            <div class="row w-100" v-if="loading">
                <div class="loading-spinner"></div>
            </div>
            <div class="row w-100" v-else>
                <div class="row w-100" v-for="(variant, index2) in variants" :key="index2">
                    <input type="hidden" :name="'variants[' + index2 + '][variant_id]'" :value="variant.id">
                    <div class="col-xl-12 col-sm-12 col-xs-12 p-2">
                        <label class="col-form-label" style="font-weight: bold;"> متغییر شماره @{{ index2+1 }}</label>
                        <button type="button"
                                @click="deleteVariant(index2)"
                                class="btn btn-sm me-2 align-items-center"
                        >
                            <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                        </button>
                    </div>
                    <div v-for="(specId, specIndex) in mainVariantSpecificationId" :key="specId" class="col-xxl-6 col-sm-6 p-2">

                        <div class="form-group">
                            <label class="form-label" for="specification">
                                @{{ getSpecificationTitle(specId) }}
                            </label>
                            <div class="position-relative mb-2 border overflow-hidden rounded-5 m-1 row">
                                <input type="text" v-model="valueTitles[specId]"
                                       class="form-control form-control-sm rounded-5 border-0"
                                       :style="getSpecification(specId).is_color == 1 ? 'height: 100%;width:40%' : 'height: 100%;width:80%'"
                                       placeholder="عنوان مقدار جدید">
                                <input v-if="getSpecification(specId).is_color == 1" style="width:40%" type="color"
                                       v-model="valueColors[specId]"
                                       class="form-control form-control-sm rounded-5 border-0"
                                       placeholder="انتخاب رنگ">
                                <button @click="saveValue(specId)"
                                        class="btn btn-success btn-sm p-2 rounded-0 border-0 shadow-none position-absolute top-0 bottom-0 end-0"
                                        type="button" style="width: 20%;">
                                <span>
                                    <i class="bi bi-download"></i>
                                    افزودن
                                </span>
                                </button>
                            </div>
                            <v-select
                                requiredCms
                                v-model="variant.specifications[specId]"
                                :options="getSpecificationOptions(specId)"
                                :reduce="item => item.id"
                                key="id"
                                label="title"
                                name="specifications"
                                placeholder="انتخاب کنید"
                                searchable
                            >
                            </v-select>
                            <input type="hidden" :name="'variants[' + index2 + '][specifications][' + specId + ']'" :value="variant.specifications[specId]">
                        </div>

                    </div>

                    <div class="row w-100 m-0">
                        <div class="col-xl-6 col-sm-12 col-xs-12 p-2">
                            <label class="form-label">
                                قیمت (تومان)
                            </label>
                            <input numberCms class="form-control rounded-custom" :name="'variants[' + index2 + '][price]'" placeholder="قیمت (تومان) را وارد کنید..." v-model="variant.price" requiredCms>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-xs-12 p-2">
                            <label class="form-label">
                                قیمت با تخفیف (تومان)
                            </label>
                            <input numberCms class="form-control rounded-custom" :name="'variants[' + index2 + '][discounted_price]'" placeholder="قیمت با تخفیف (تومان) را وارد کنید..." v-model="variant.discounted_price">
                        </div>
                        <div class="col-xl-6 col-sm-12 col-xs-12 p-2">
                            <label class="form-label">
                                موجودی
                            </label>
                            <input numberCms class="form-control rounded-custom" :name="'variants[' + index2 + '][stock]'" placeholder="موجودی را وارد کنید..." v-model="variant.stock" requiredCms>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-xs-12 p-2">
                            <label class="form-label">
                                وزن (گرم)
                            </label>
                            <input numberCms class="form-control rounded-custom" :name="'variants[' + index2 + '][weight]'" placeholder="وزن (گرم) را وارد کنید..." v-model="variant.weight" requiredCms>
                        </div>
                    </div>
                    <hr v-if="index2 < variants.length - 1" class="w-100 mt-4 mb-4" />
                </div>
            </div>

        </div>
    </div>
    <div class="w-100 pe-0 text-end" v-if="variants.length > 0">
        <button class="btn btn-custom rounded-custom w-fit px-3 py-2" type="submit">
            ذخیره
        </button>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        new Vue({
            el: "#spf-elements",
            data: {
                productId: {{ @$product->id }},
                mainVariantSpecificationId: @json($product->main_specifications->pluck('id')->toArray()),
                specifications: @json($sortedSpecifications),
                selectedSpecificationIds: @json($product->specifications),
                sortedSpecifications: @json($sortedSpecifications),
                selectedSps: [],
                selectSpecifications: [],
                loading: false,
                valueTitles: {},
                valueColors: {},
                //
                variants: [], // مقدار اولیه variants را خالی تعریف می‌کنیم
            },
            methods: {
                handleMainVariantChange(value) {
                    this.mainVariantSpecificationId = value;
                },
                getSpecification(specId) {
                    return this.specifications.find(spec => spec.id === specId) || {};
                },
                getSpecificationTitle(specId) {
                    const spec = this.getSpecification(specId);
                    return spec ? spec.title : '';
                },
                getSpecificationOptions(specId) {
                    const spec = this.getSpecification(specId);
                    return spec ? spec.children : [];
                },
                sortSpecifications() {
                    this.selectSpecifications = this.specifications.filter(spec =>
                        this.mainVariantSpecificationId.includes(spec.id) && spec.type === 'select'
                    );
                },
                addValue(specification) {
                    specification.product_values.push({value: ""});
                },
                async saveValue(specId) {
                    try {
                        if (this.valueTitles[specId] && this.valueTitles[specId].trim().length !== 0) {
                            let formData = new FormData();
                            formData.append("title", this.valueTitles[specId]);
                            formData.append("color_code", this.valueColors[specId] || '');
                            formData.append("parent_id", specId);
                            const response = await axios.post('{{ route('admin.specification-value.save-values') }}', formData);
                            const newValue = {
                                id: response.data.id,
                                title: this.valueTitles[specId],
                                color_code: this.valueColors[specId] || ''
                            };
                            const spec = this.getSpecification(specId);
                            if (spec) {
                                spec.children.push(newValue);
                            }
                            Swal.fire({
                                icon: 'success',
                                text: "با موفقیت ذخیره شد",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: "لطفا مقدار مشخصه را پر کنید",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        }
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            text: "با خطا مواجه شدید",
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    } finally {
                        this.$set(this.valueTitles, specId, '');
                        this.$set(this.valueColors, specId, '');
                    }
                },
                addVariant() {
                    const newVariant = {
                        id: null,
                        price: "",
                        discounted_price: "",
                        stock: "",
                        weight: "",
                        specifications: {}
                    };

                    this.mainVariantSpecificationId.forEach(specId => {
                        newVariant.specifications[specId] = null;
                    });

                    this.variants.push(newVariant);
                },
                async deleteVariant(index) {
                    if (this.variants[index].id) {
                        await this.deleteMainVariant(this.variants[index].id);
                    }
                    this.variants.splice(index, 1);
                },
                async deleteMainVariant(id) {
                    this.loading = true;
                    try {
                        await axios.get('{{ route('admin.product-variant.delete') }}/' + id);
                        await this.getVariants();
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
                async getVariants() {
                    this.loading = true;
                    try {
                        const response = await axios.get(`{{ route('admin.product-variant.list') }}?product_id=${this.productId}`);
                        this.variants = response.data.variants.map(variant => {
                            const newVariant = {
                                id: variant.id,
                                price: variant.price,
                                discounted_price: variant.discounted_price,
                                stock: variant.stock,
                                weight: variant.weight,
                                specifications: {}
                            };

                            variant.specifications.forEach(spec => {
                                newVariant.specifications[spec.parent_id] = spec.id;
                            });

                            return newVariant;
                        });
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
                initializeSelectedSps() {
                    this.selectSpecifications.forEach(spec => {
                        let specifications = this.selectedSpecificationIds;
                        let filteredSpecs = specifications.filter(product_specification => {
                            return product_specification.parent_id == spec.id;
                        }).map(filtered_spec => {
                            return filtered_spec.id;
                        });
                        this.$set(this.selectedSps, spec.id, filteredSpecs);
                    });
                },
                submitForm(event) {
                    event.preventDefault();
                    if (this.validateForm(event.target)) {
                        event.target.submit();
                    }
                },
                validateForm(form) {
                    const inputs = form.querySelectorAll('input, select, textarea, .v-select');
                    this.resetErrorElements(inputs);
                    const errors = this.errorsGenerator(inputs);
                    if (errors.length > 0) {
                        errors.forEach(error => {
                            const errorElement = document.createElement('p');
                            errorElement.textContent = error.errors[0].message;
                            errorElement.style.color = "red";
                            errorElement.style.display = 'block';
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
                    }
                    return true;
                },
                errorsGenerator(inputs) {
                    const inputErrors = [];
                    inputs.forEach(element => {
                        const errors = [];
                        let isEmpty = false;


                        if (element.hasAttribute("requiredCms") && isEmpty) {
                            errors.push({ message: "وارد کردن مقدار الزامیست", validation: "requiredCms" });
                        }

                        if (element.hasAttribute("numberCms") && !isEmpty) {
                            const numberPattern = /^[\d۰۱۲۳۴۵۶۷۸۹]+$/;
                            if (!numberPattern.test(element.value)) {
                                errors.push({ message: "فقط عدد وارد کنید", validation: "numberCms" });
                            }
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
                },
                clearError(element) {
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
                },
                sanitizeValueNumber(value) {
                    const validCharacters = /[۰-۹0-9]/g;
                    return value.match(validCharacters)?.join('') || '';
                },
            },
            watch: {
            mainVariantSpecificationId: {
                handler(newVal) {
                },
                deep: true
            }
        },
            async mounted() {
                await this.getVariants();
                await this.sortSpecifications();
                this.initializeSelectedSps();
                let vm = this;
                let numberInputs = document.querySelectorAll("[numberCms]");
                numberInputs.forEach(item => {
                    item.addEventListener('input', function (event) {
                        event.target.value = vm.sanitizeValueNumber(event.target.value);
                    });
                });
            },
        });
    </script>
@endpush
@push('styles')
    <style>
        .loading-spinner {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 3px solid rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            border-top-color: #007bff;
            animation: spin 1s ease-in-out infinite;
            margin: auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

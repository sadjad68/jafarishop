<div id="spf-elements" class="p-0">
    <div class="bg-light border p-3 rounded-5 mb-3 position-relative">
        <h4 class="p-0" style="font-weight: bold;">
            مشخصات نوشتاری
        </h4>
        <hr>
        <div v-for="(specification, index2) in textSpecifications" :key="specification.id">
            <button type="button"
                    @click="addValue(specification)"
                    class="btn btn-outline-success rounded-custom btn-sm align-items-center"
            >
                <i class="bi bi-plus d-flex my-0"></i>
            </button>
            <label class="col-form-label">
                @{{ specification.title }} :
            </label>

            <div class="row w-100 m-0" v-for="(product_value, index) in specification.product_values.filter(pv => pv.product_id === {{$product->id}})"
                 :key="index">
                <div class="col-xl-11 col-sm-12 col-xs-12 p-2">
                    <input type="hidden"
                           :name="'specification.product_values[' + index2 + '][' + index + '][product_value_id]'"
                           :value="product_value?.id">
                    <input type="hidden"
                           :name="'specification.product_values[' + index2 + '][' + index + '][specification_id]'"
                           :value="specification?.id">

                    <textarea class="form-control rounded-custom"
                              :name="'specification.product_values[' + index2 + '][' + index + '][value]'"
                              :placeholder="'مقدار '+specification.title+' را وارد کنید...'"
                              v-model="product_value.value"></textarea>
                </div>
                <div class="col-xl-1 p-2 d-flex align-items-center justify-content-between">
                    <button type="button"
                            @click="deleteValue(specification,product_value)"
                            class="btn btn-sm me-2 align-items-center"
                    >
                        <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light border p-3 rounded-5 mb-3 position-relative">
        <h4 class="p-0" style="font-weight: bold;">
            مشخصات انتخابی و فیلترها
        </h4>
        <hr>

        <div class="row w-100">
            <div v-for="specification in selectSpecifications" :key="specification.id" class="col-xxl-6 col-sm-6 p-2">
                <div class="form-group">
                    <label for="discounted_price">
                        @{{ specification.title }}
                    </label>

                    <div class="position-relative mb-2 border overflow-hidden rounded-5 m-1 row">
                        <input type="text" v-model="valueTitles[specification.id]"
                               class="form-control form-control-sm rounded-5 border-0"
                               :style="specification.is_color == 1 ? 'height: 100%;width:40%' : 'height: 100%;width:80%'"
                               placeholder="عنوان مقدار جدید">
                        <input v-if="specification.is_color == 1" style="width:40%" type="color"
                               v-model="valueColors[specification.id]"
                               class="form-control form-control-sm rounded-5 border-0"
                               placeholder="انتخاب رنگ">
                        <button @click="saveValue(specification.id)"
                                class="btn btn-success btn-sm p-2 rounded-0 border-0 shadow-none position-absolute top-0 bottom-0 end-0"
                                type="button" style="width: 20%;">
                               <span>
                                    <i class="bi bi-download"></i>
                                   افزودن
                               </span>
                        </button>
                    </div>
                    <v-select
                        multiple
                        :value="selectedSps[specification.id]"
                        @input="setSelected($event,specification.id)"
                        :options="specification.children"
                        :reduce="item => item.id"
                        key="id"
                        label="title"
                        name="specifications"
                        placeholder="انتخاب کنید"
                    >
                    </v-select>
                    <input type="hidden" name="specifications[]"
                           v-for="item in selectedSps[specification.id]"
                           v-if="item !== null"
                           :value="item">
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        new Vue({
            el: "#spf-elements",
            data: {
                productId: {{ @$product->id }},
                specifications: @json($sortedSpecifications),
                selectedSpecificationIds: @json($product->specifications),

                selectedSps: [],
                textSpecifications: [],
                selectSpecifications: [],
                loading: false,
                spsOptions: [
                    {
                        name: "",
                        id: ""
                    }
                ],
                valueTitles: {},
                valueColors: {},
            },
            methods: {
                sortSpecifications() {
                    this.textSpecifications = this.specifications.filter(spec => spec.type === 'text');
                    // this.addValue(this.textSpecifications[0]);
                    this.selectSpecifications = this.specifications.filter(spec => spec.type === 'select');
                },
                addValue(specification) {
                    console.log(specification)
                    specification.product_values.push({value: "",product_id: {{$product->id}}});
                },
                async deleteValue(specification, productValue) {
                    if (productValue?.id) {
                        await this.deleteMainValue(productValue.id);
                    }

                    specification.product_values = specification.product_values.filter(
                        pv => pv !== productValue
                    );
                },
                async deleteMainValue(id) {
                    this.loading = true;
                    try {
                        await axios.get('{{ route('admin.product-property-spf-tag.delete-product-specification') }}/' + id);
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
                async saveValue(specificationId) {
                    try {
                        if(this.valueTitles[specificationId] && this.valueTitles[specificationId].trim().length !== 0){
                            let formData = new FormData();
                            formData.append("title", this.valueTitles[specificationId]);
                            formData.append("color_code", this.valueColors[specificationId]);
                            formData.append("parent_id", specificationId);
                            const response = await axios.post('{{ route('admin.specification-value.save-values') }}', formData);
                            // مقدار جدید ذخیره‌شده را به children مربوطه اضافه کنید
                            const newValue = {
                                id: response.data.id, // فرض کنید سرور ID جدید را برمی‌گرداند
                                title: this.valueTitles[specificationId],
                                color_code: this.valueColors[specificationId]
                            };

                            // پیدا کردن مشخصه مورد نظر
                            const specification = this.selectSpecifications.find(spec => spec.id === specificationId);
                            if (specification) {
                                specification.children.push(newValue);
                            }
                            Swal.fire({
                                icon: 'success',
                                text: "با موفقیت ذخیره شد",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        } else{
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
                    }
                    this.$set(this.valueTitles, specificationId, ''); // پاکسازی ورودی متنی برای مشخصات فعلی
                    this.$set(this.valueColors, specificationId, ''); // پاکسازی ورودی متنی برای مشخصات فعلی
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
                        this.$set(this.valueTitles, spec.id, '');
                        this.$set(this.valueColors, spec.id, '');
                    });
                },

                setSelected(event, id) {
                    this.$set(this.selectedSps, id, event);
                }
            },
            async mounted() {
                await this.sortSpecifications();
                this.initializeSelectedSps();
            },
            watch: {
                selectedSps(val) {
                    console.log('selectedSps');
                    console.log(val);
                }
            }
        });
    </script>
@endpush






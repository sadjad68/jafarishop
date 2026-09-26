<div id="property-elements" class="bg-light border p-3 rounded-5 mb-3 position-relative">
    <h4 class="p-0" style="font-weight: bold;">
        ویژگی ها
    </h4>
    <button type="button"
            @click="addProperties"
            class="btn btn-lg btn-outline-success rounded-custom btn-sm btn-add-form d-flex align-items-center"
    >
        <i class="bi bi-plus d-flex my-0"></i>
        افزودن
    </button>
    <hr>
    <div class="bg-light mt-1">
        <div class="row w-100 m-0" v-for="(property, index) in properties" :key="index">
            <div class="col-xl-11 col-sm-12 col-xs-12 p-2">
                <input type="hidden" :name="'properties[' + index + '][property_id]'" :value="property?.id">
                <input class="form-control rounded-custom" :name="'properties[' + index + '][value]'" placeholder="ویژگی"
                          v-model="property.value" />
            </div>
            <div class="col-xl-1 p-2 d-flex align-items-center justify-content-between">
                <button type="button"
                        v-if="properties.length > 1"
                        @click="deleteProperties(index)"
                        class="btn btn-sm me-2 align-items-center"
                >
                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        new Vue({
            el: "#property-elements",
            data: {
                productId: {{ @$product->id }},
                properties: @json($properties) ,
                loading: false,
                msgProp : "hi"
            },
            computed: {},
            methods: {
                addProperties() {
                    this.properties.push({value: ""});
                },
                async deleteProperties(propertyIndex) {
                    if (this.properties[propertyIndex]?.id) {
                        await this.deleteMain(this.properties[propertyIndex].id);
                    }
                    this.properties = this.properties.filter((_, index) => {
                        return index !== propertyIndex
                    });
                },
                async deleteMain(id) {
                    this.loading = true;
                    try {
                        await axios.get('{{ route('admin.product-property-spf-tag.delete-prop') }}/' + id);
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
            },
            async mounted() {
                if (this.properties.length === 0) {
                    this.addProperties();
                }
            }
        });
    </script>
@endpush

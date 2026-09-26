@push('styles')
    <link rel="stylesheet" href="{{asset("assets/admin/css/vue-select.css")}}" />
@endpush

<div id="excelModal" class="modal fade text-dark" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="overflow: unset;">
        <div class="modal-content rounded-custom border-custom shadow bg-white">

            <div class="modal-header px-3 py-2">
                <h4 class="m-0">خروجی</h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body p-2" style="overflow: unset;">
                <form class="m-0">

                    <div id="basketExcelApp" class="row w-100 m-0">

                        <!-- فیلتر کاربر -->
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input p-0 ms-2 m-0"
                                   type="checkbox"
                                   id="filter_hasUser"
                                   v-model="filterData.filter_hasUser">
                            <label class="form-check-label fw-bold" for="filter_hasUser">
                                کاربر ناشناس نباشد
                            </label>
                        </div>

                        <!-- کاربران -->
                        <div class="mt-2" v-show="filterData.filter_hasUser">
                            <label class="mb-1 fw-bold">کاربران</label>

                            <v-select
                                v-model="filterData.users"
                                :options="userList"
                                :multiple="true"
                                :filterable="false"
                                label="full_name"
                                placeholder="انتخاب کاربران..."
                                :loading="loadingState.users"
                                @search="loadUserList"
                            ></v-select>
                        </div>

                        <!-- موبایل -->
                        <div class="mt-2">
                            <label class="mb-1 fw-bold">موبایل</label>
                            <input type="text"
                                   v-model="filterData.mobile"
                                   class="form-control"
                                   placeholder="موبایل کاربر">
                        </div>

                        <!-- محصولات -->
                        <div class="mt-2">
                            <label class="mb-1 fw-bold">محصولات</label>

                            <v-select
                                v-model="filterData.products"
                                :options="productList"
                                :multiple="true"
                                label="title"
                                placeholder="انتخاب محصولات..."
                                :loading="loadingState.products"
                                @search="loadProductList"
                            ></v-select>
                        </div>

                        <!-- فیلتر آدرس -->
                        <div class="mt-2">
                            <div class="form-check d-flex align-items-center px-0">
                                <input class="form-check-input p-0 ms-2 m-0"
                                       type="checkbox"
                                       id="filter_hasAddress"
                                       v-model="filterData.filter_hasAddress">
                                <label class="form-check-label fw-bold" for="filter_hasAddress">
                                    دارای آدرس باشد
                                </label>
                            </div>

                            <transition name="fade">
                                <div v-if="filterData.filter_hasAddress" class="mt-2">

                                    <input type="text"
                                           v-model="filterData.filter_postalCode"
                                           class="form-control mb-2"
                                           placeholder="کد پستی (اختیاری)">

                                    <select v-model="filterData.filter_stateId" class="form-select mb-2">
                                        <option value="">انتخاب استان</option>
                                        <option v-for="state in stateList" :key="state.id" :value="state.id">
                                            @{{ state.name }}
                                        </option>
                                    </select>

                                    <select v-model="filterData.filter_cityId" class="form-select">
                                        <option value="">انتخاب شهر</option>
                                        <option v-for="city in cityList" :key="city.id" :value="city.id">
                                            @{{ city.name }}
                                        </option>
                                    </select>

                                </div>
                            </transition>
                        </div>

                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label>از تاریخ</label>
                                <input
                                    type="text"
                                    id="from_date"
                                    class="form-control bg-light rounded-custom"
                                    placeholder="از تاریخ"
                                    v-model="filterData.from_date"
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label>تا تاریخ</label>
                                <input
                                    type="text"
                                    id="to_date"
                                    class="form-control bg-light rounded-custom"
                                    placeholder="تا تاریخ"
                                    v-model="filterData.to_date"
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <!-- دکمه خروجی -->
                        <div class="col-12 ms-auto p-2">
                            <button type="button"
                                    class="btn btn-custom rounded-custom w-100"
                                    @click="applyFilters">
                                <i class="bi bi-download"></i> خروجی
                            </button>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@push("scripts")
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $("#datepicker3").datepicker({--}}
{{--                changeMonth: true,--}}
{{--                changeYear: true--}}
{{--            });--}}
{{--        });--}}
{{--        $(document).ready(function() {--}}
{{--            $("#datepicker4").datepicker({--}}
{{--                changeMonth: true,--}}
{{--                changeYear: true--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}

    <script>

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        Vue.component('v-select', VueSelect.VueSelect);

        new Vue({
            el: '#basketExcelApp',

            data() {
                return {
                    loadingState: { users: false, products: false },

                    userList: [],
                    productList: [],
                    stateList: @json($states ?? []),
                    cityList: [],

                    filterData: {
                        users: [],
                        products: [],
                        mobile: "{{ request('mobile') }}",
                        filter_hasAddress: {{ request('has_address') ? 'true' : 'false' }},
                        filter_hasUser: {{ request('has_user') ? 'true' : 'false' }},
                        filter_stateId: "{{ request('state_id') }}",
                        filter_cityId: "{{ request('city_id') }}",
                        filter_postalCode: "{{ request('postal_code') }}",

                        from_date: "{{ request('from_date') }}",
                        to_date: "{{ request('to_date') }}"
                    },

                    initialUserIds:
                        "{{ request('user_ids') }}" ?
                            "{{ request('user_ids') }}".split(',').filter(Boolean).map(Number) : [],

                    initialProductIds:
                        "{{ request('product_ids') }}" ?
                            "{{ request('product_ids') }}".split(',').filter(Boolean).map(Number) : [],
                }
            },

            mounted() {
                this.loadUserList('');
                this.loadProductList('');

                if (this.filterData.filter_stateId) {
                    this.loadCities(this.filterData.filter_stateId);
                }

                if (this.initialUserIds.length) this.preloadUsersByIds(this.initialUserIds);
                if (this.initialProductIds.length) this.preloadProductsByIds(this.initialProductIds);

                this.$nextTick(() => {
                    const vm = this;
                    $('#from_date').datepicker({
                        autoclose: true,
                    }).on('change', function (e) {
                        vm.filterData.from_date = e.target.value;
                    });

                    $('#to_date').datepicker({
                        autoclose: true,
                    }).on('change', function (e) {
                        vm.filterData.to_date = e.target.value;
                    });
                });
            },


            methods: {
                loadCities(stateId) {
                    axios.get(`{{ url('/admin/basket/get-cities') }}?state_id=${stateId}`)
                        .then(res => this.cityList = res.data);
                },

                loadProductList: debounce(function (search) {
                    this.loadingState.products = true;

                    axios.get(`{{ url('/admin/basket/get-products') }}?search=${search || ''}`)
                        .then(res => {
                            const selected = this.filterData.products;
                            this.productList = [
                                ...selected,
                                ...res.data.filter(p => !selected.some(sp => sp.id === p.id))
                            ];
                        })
                        .finally(() => this.loadingState.products = false);
                }, 350),

                loadUserList: debounce(function (search) {
                    this.loadingState.users = true;

                    axios.get(`{{ url('/admin/basket/get-users') }}?search=${search || ''}`)
                        .then(res => {
                            const selected = this.filterData.users;
                            this.userList = [
                                ...selected,
                                ...res.data.filter(u => !selected.some(su => su.id === u.id))
                            ];
                        })
                        .finally(() => this.loadingState.users = false);
                }, 350),

                preloadUsersByIds(ids) {
                    axios.get(`{{ url('/admin/basket/get-users') }}?ids=${ids.join(',')}`)
                        .then(res => {
                            this.userList.push(...res.data);
                            this.filterData.users = res.data;
                        });
                },

                preloadProductsByIds(ids) {
                    axios.get(`{{ url('/admin/basket/get-products') }}?ids=${ids.join(',')}`)
                        .then(res => {
                            this.productList.push(...res.data);
                            this.filterData.products = res.data;
                        });
                },

                applyFilters() {
                    let params = new URLSearchParams();

                    if (this.filterData.users.length)
                        params.append('user_ids', this.filterData.users.map(u => u.id).join(','));

                    if (this.filterData.products.length)
                        params.append('product_ids', this.filterData.products.map(p => p.id).join(','));

                    if (this.filterData.mobile)
                        params.append('mobile', this.filterData.mobile);

                    if (this.filterData.filter_hasAddress)
                        params.append('has_address', 1);

                    if (this.filterData.filter_hasUser)
                        params.append('has_user', 1);

                    if (this.filterData.filter_stateId)
                        params.append('state_id', this.filterData.filter_stateId);

                    if (this.filterData.filter_cityId)
                        params.append('city_id', this.filterData.filter_cityId);

                    if (this.filterData.filter_postalCode)
                        params.append('postal_code', this.filterData.filter_postalCode);

                    if (this.filterData.from_date)
                        params.append('from_date', this.filterData.from_date);

                    if (this.filterData.to_date)
                        params.append('to_date', this.filterData.to_date);

                    window.location = `/admin/basket/export/?${params.toString()}`;
                }
            },

            watch: {
                'filterData.filter_stateId'(newVal) {
                    if (newVal) this.loadCities(newVal);
                    else this.cityList = [];
                }
            }
        });
    </script>
@endpush

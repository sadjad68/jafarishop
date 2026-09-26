@push('styles')
    <link rel="stylesheet" href="{{asset("assets/admin/css/vue-select.css")}}"/>
@endpush
<div id="searchModal" class="modal fade text-dark" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="overflow: unset;">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">جستجو</h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2" style="overflow: unset;">
                <form class="m-0" id="cartFilterForm">
                    <input type="hidden" name="filter" value="1">
                    <div id="cart-search" class="row w-100 m-0">
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input p-0 ms-2 m-0" type="checkbox" id="has_user"
                                   v-model="filters.has_user">
                            <label class="form-check-label fw-bold" for="has_user">
                                کاربر ناشناس نباشد
                            </label>
                        </div>
                        {{-- کاربران --}}
                        <div class="mt-2" v-show="filters.has_user">
                            <label class="mb-1 fw-bold">کاربران</label>
                            <v-select
                                v-model="filters.users"
                                :options="users"
                                :multiple="true"
                                :filterable="false"
                                label="full_name"
                                placeholder="انتخاب کاربران..."
                                :loading="loading.users"
                                @search="fetchUsers"
                            ></v-select>

                        </div>
                        <div class="mt-2">
                            <label class="mb-1 fw-bold">موبایل</label>
                            <input type="text" v-model="filters.mobile" class="form-control" placeholder="موبایل کاربر">
                        </div>
                        {{-- محصولات --}}
                        <div class="mt-2">
                            <label class="mb-1 fw-bold">محصولات</label>
                            <v-select
                                v-model="filters.products"
                                :options="products"
                                :multiple="true"
                                label="title"
                                placeholder="انتخاب محصولات..."
                                :loading="loading.products"
                                @search="fetchProducts"
                            ></v-select>

                        </div>

                        {{-- فیلتر آدرس --}}
                        <div class="mt-2">
                            <div class="form-check d-flex align-items-center px-0">
                                <input class="form-check-input p-0 ms-2 m-0" type="checkbox" id="has_address"
                                       v-model="filters.has_address">
                                <label class="form-check-label fw-bold" for="has_address">
                                    دارای آدرس باشد
                                </label>
                            </div>
                            <transition name="fade">
                                <div v-if="filters.has_address" class="mt-2">
                                    <input type="text" v-model="filters.postal_code" class="form-control mb-2"
                                           placeholder="کد پستی (اختیاری)">
                                    <select v-model="filters.state_id" class="form-select mb-2">
                                        <option value="">انتخاب استان</option>
                                        <option v-for="state in states" :key="state.id" :value="state.id">@{{ state.name
                                            }}
                                        </option>
                                    </select>
                                    <select v-model="filters.city_id" class="form-select">
                                        <option value="">انتخاب شهر</option>
                                        <option v-for="city in cities" :key="city.id" :value="city.id">@{{ city.name
                                            }}
                                        </option>
                                    </select>
                                </div>
                            </transition>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label class="fw-bold mb-1">از تاریخ</label>
                                <input
                                    type="text"
                                    id="search_from_date"
                                    class="form-control bg-light rounded-custom"
                                    placeholder="از تاریخ"
                                    v-model="filters.from_date"
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label class="fw-bold mb-1">تا تاریخ</label>
                                <input
                                    type="text"
                                    id="search_to_date"
                                    class="form-control bg-light rounded-custom"
                                    placeholder="تا تاریخ"
                                    v-model="filters.to_date"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        {{-- دکمه جستجو --}}
                        <div class="col-12 ms-auto p-2">
                            <button type="button" class="btn btn-custom rounded-custom w-100" @click="submitFilters">
                                <i class="bi bi-search"></i> جستجو
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    /* برای دیت‌پیکر بوت‌استرپ */
    .datepicker.dropdown-menu {
        z-index: 9999 !important;
    }
    /* اگر از پکیج دیگری استفاده شده باشد */
    .datepicker-container {
        z-index: 9999 !important;
    }
</style>
@push("scripts")
    <script>
        // ۱. تعریف تابع دی‌بانس
        function debounce(fn, delay) {
            let timeoutID = null;
            return function () {
                clearTimeout(timeoutID);
                let args = arguments;
                let that = this;
                timeoutID = setTimeout(function () {
                    fn.apply(that, args);
                }, delay);
            };
        }

        // ۲. ثبت جهانی کامپوننت v-select (برای رفع خطای کنسول)
        Vue.component('v-select', VueSelect.VueSelect);

        new Vue({
            el: '#searchModal',
            data() {
                return {
                    loading: {users: false, products: false},
                    users: [],
                    products: [],
                    states: @json($states ?? []),
                    cities: [],
                    filters: {
                        users: [],
                        products: [],
                        mobile: "{{ request('mobile') }}",
                        has_address: {{ request('has_address') ? 'true' : 'false' }},
                        has_user: {{ request('has_user') ? 'true' : 'false' }},
                        state_id: "{{ request('state_id') }}",
                        city_id: "{{ request('city_id') }}",
                        postal_code: "{{ request('postal_code') }}",
                        from_date: "{{ request('from_date') }}",
                        to_date: "{{ request('to_date') }}"
                    }
                }
            },
            mounted() {
                const vm = this;
                $('#searchModal').on('shown.bs.modal', function () {
                    vm.initDatePickers();
                });
            },
            methods: {
                initDatePickers() {
                    const vm = this;
                    const options = {
                        autoclose: true,
                        rtl: true,
                        language: 'fa',
                        format: 'yyyy/mm/dd',
                        orientation: "bottom auto"
                    };

                    // چک می‌کنیم کدام تابع در دسترس است (datepicker یا bootstrapDP)
                    const dpFunc = $.fn.bootstrapDP ? 'bootstrapDP' : 'datepicker';

                    // مقداردهی اینپوت "از تاریخ"
                    $('#search_from_date')[dpFunc](options).on('changeDate', function (e) {
                        vm.filters.from_date = $(this).val();
                    }).on('change', function(){
                        // برای اطمینان از سینک شدن دستی
                        vm.filters.from_date = $(this).val();
                    });

                    // مقداردهی اینپوت "تا تاریخ"
                    $('#search_to_date')[dpFunc](options).on('changeDate', function (e) {
                        vm.filters.to_date = $(this).val();
                    }).on('change', function(){
                        vm.filters.to_date = $(this).val();
                    });
                },
                fetchProducts: debounce(function (search) {
                    if(!search) return;
                    this.loading.products = true;
                    axios.get(`{{ url('/admin/basket/get-products') }}?search=${search}`)
                        .then(res => {
                            const selectedProducts = this.filters.products || [];
                            this.products = [...selectedProducts, ...res.data.filter(p => !selectedProducts.some(sp => sp.id === p.id))];
                        })
                        .finally(() => {
                            this.loading.products = false;
                        });
                }, 400),
                fetchUsers: debounce(function (search) {
                    if(!search) return;
                    this.loading.users = true;
                    axios.get(`{{ url('/admin/basket/get-users') }}?search=${search}`)
                        .then(res => {
                            const selectedUsers = this.filters.users || [];
                            this.users = [...selectedUsers, ...res.data.filter(u => !selectedUsers.some(su => su.id === u.id))];
                        })
                        .finally(() => {
                            this.loading.users = false;
                        });
                }, 400),
                submitFilters() {
                    let params = new URLSearchParams();

                    if (this.filters.users && this.filters.users.length)
                        params.append('user_ids', this.filters.users.map(u => u.id).join(','));

                    if (this.filters.products && this.filters.products.length)
                        params.append('product_ids', this.filters.products.map(p => p.id).join(','));

                    if (this.filters.mobile) params.append('mobile', this.filters.mobile);
                    if (this.filters.has_address) params.append('has_address', 1);
                    if (this.filters.has_user) params.append('has_user', 1);
                    if (this.filters.state_id) params.append('state_id', this.filters.state_id);
                    if (this.filters.city_id) params.append('city_id', this.filters.city_id);
                    if (this.filters.postal_code) params.append('postal_code', this.filters.postal_code);
                    if (this.filters.from_date) params.append('from_date', this.filters.from_date);
                    if (this.filters.to_date) params.append('to_date', this.filters.to_date);

                    params.append('filter', '1');
                    window.location.href = window.location.pathname + '?' + params.toString();
                }
            },
            watch: {
                'filters.state_id'(newVal) {
                    if (newVal) {
                        axios.get(`{{ url('/admin/basket/get-cities') }}?state_id=${newVal}`).then(res => {
                            this.cities = res.data;
                        });
                    } else {
                        this.cities = [];
                        this.filters.city_id = '';
                    }
                }
            }
        });
    </script>
@endpush

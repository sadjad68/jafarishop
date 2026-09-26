<div id="searchModal" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="overflow: unset;">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    جستجو
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2" style="overflow: unset;">
                <form method="GET" action="{{URL::current()}}" class="m-0">
                    <input type="hidden" name="filter">
                    <div class="row w-100 m-0">
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="title"
                                    label="عنوان"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-input
                                    name="url"
                                    label="url"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-select
                                    name="brand_id"
                                    label="برند"
                                    :options="@$brand ? $brand : []"
                                    optionValue="id"
                                    optionLabel="title"
                                    :searchable="true"
                                    :selectedOption="null"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <label>
                                    سازنده
                                </label>
                                <select name="creator_id" class="w-100 form-select bg-light rounded-custom " >
                                    <option value="">سازنده را انتخاب کنید</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <label class="mb-1">دسته بندی</label>
                                <select
                                    id="categorySelect"
                                    name="category_id"
                                    class="form-control"
                                    data-selected-id="null"
                                ></select>
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <label>
                                    وضعیت موجودی
                                </label>
                                <select name="stock_status" class="w-100 form-select bg-light rounded-custom " >
                                    <option value="">همه</option>
                                    <option value="stock">موجود</option>
                                    <option value="out_of_stock">ناموجود</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 p-2 mt-3">
                            <div class="form-group">
                                <x-cms-check-box
                                    name="show_in_first_page"
                                    label="نمایش در صفحه اول"
                                    :valueData="@$data"
                                    :value="0"
                                />
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">
                            <button type="submit" class="btn btn-custom rounded-custom w-100">
                                <i class="bi bi-search"></i>
                                جستجو
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('styles')
    <link href="{{ asset("assets/admin/css/select2.min.css") }}" rel="stylesheet"/>
    <style>
        /* هماهنگی با ظاهر فیلدهای پروژه شما (bg-light & rounded-custom) */
        .select2-container--default .select2-selection--single {
            background-color: #f8f9fa !important; /* دقیقا bg-light */
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important; /* دقیقا مشابه rounded-custom */
            height: 40px !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection__rendered {
            color: #212529 !important;
            padding-right: 12px !important;
            font-family: 'iransans', sans-serif !important;
            font-size: 14px;
        }

        /* رفع مشکل افتادن زیر مودال */
        .select2-container--open {
            z-index: 9999 !important;
        }
        .select2-dropdown {
            z-index: 9999;
            border: 1px solid #7367f0 !important;
            border-radius: 8px !important;
        }

        /* استایل هایلایت بنفش (طبق کد خودت) */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7367f0 !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #e9ecef;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset("assets/admin/js/select2.min.js") }}"></script>
    <script>
        $(document).ready(function () {
            $('#categorySelect').select2({
                dir: "rtl",
                placeholder: "انتخاب دسته بندی...",
                allowClear: true,
                dropdownParent: $('#searchModal'),
                ajax: {
                    url: '{{ route('admin.product.form-categories') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: { more: data.has_more_pages }
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush

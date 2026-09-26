<div id="excelModal" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="overflow: unset;">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    آپدیت موجودی و قیمت
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2" style="overflow: unset;">
                <form method="GET" action="{{route('admin.product.export')}}" class="m-0">
                    <div class="row w-100 m-0">
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <x-cms-select
                                    name="brand_id"
                                    label="برند"
                                    :options="$brand ? $brand : []"
                                    optionValue="id"
                                    optionLabel="title"
                                    :searchable="true"
                                    :selectedOption="null"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="form-group">
                                <label class="mb-1">دسته بندی</label>
                                <select
                                    id="categorySelectExcel"
                                    name="category_id"
                                    class="form-control"
                                    data-selected-id="null"
                                ></select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2 mt-auto">
                            <button type="submit" class="btn btn-custom rounded-custom w-100">
                                <i class="bi bi-file-excel"></i>
                                دانلود خروجی
                            </button>
                        </div>
                    </div>
                </form>
                <div class="p-1 rounded-custom bg-custom-outline mt-4 ">
                    <div class="col-md-12 p-1">
                        <div role="alert" class="text-danger">
                            توجه داشته باشید این قسمت فقط برای آپدیت موجودی و قیمت هاست و اضافه یا حذف محصولات از فایل اکسل تاثیری نخواهد داشت
                        </div>
                    </div>
                    <form method="POST" action="{{route('admin.product.import')}}" class="m-0" enctype="multipart/form-data">
                        @csrf
                        <div class="row w-100 m-0">
                            <div class="col-lg-8 p-2">
                                <div class="form-group">
                                    <x-cms-input
                                        name="excel"
                                        label="اکسل آپدیت شده را بارگذاری کنید"
                                        type="file"
                                        :validations="['requiredCms']"
                                    />
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2 mt-auto">
                                <button type="submit" class="btn btn-custom rounded-custom w-100">
                                    <i class="bi bi-plus"></i>
                                    ثبت
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .x-cms-select__dropdown {
        max-height: 300px;
        overflow-y: auto;
        box-sizing: border-box;
    }
</style>

@push('styles')
    <link href="{{ asset("assets/admin/css/select2.min.css") }}" rel="stylesheet"/>
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
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

        /* بسیار مهم برای نمایش در مودال */
        .select2-container--open {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999;
            border: 1px solid #7367f0 !important;
            border-radius: 8px !important;
        }

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
            // استفاده از رویداد shown.bs.modal برای اطمینان از رندر شدن صحیح در مودال
            $('#excelModal').on('shown.bs.modal', function () {
                $('#categorySelectExcel').select2({
                    dir: "rtl",
                    placeholder: "انتخاب دسته بندی...",
                    allowClear: true,
                    dropdownParent: $('#excelModal'), // اصلاح شده از searchModal به excelModal
                    width: '100%', // اطمینان از پر شدن عرض فیلد
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
                                pagination: {
                                    more: data.has_more_pages
                                }
                            };
                        },
                        cache: true
                    }
                });
            });

            // برای جلوگیری از مشکل فوکوس در مودال‌های بوت استرپ (اختیاری اما توصیه شده)
            $('#excelModal').on('hidden.bs.modal', function () {
                if ($('#categorySelectExcel').data('select2')) {
                    $('#categorySelectExcel').select2('destroy');
                }
            });
        });
    </script>
@endpush

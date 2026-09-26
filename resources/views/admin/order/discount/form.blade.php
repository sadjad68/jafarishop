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
            <div class="form-group">
                <label>
                    نوع کد تخفیف
                </label>
                <select name="type" class="w-100 form-select bg-light rounded-custom ">
                    <option @if(isset($data) && $data->type == "cash") selected @endif value="cash">نقدی</option>
                    <option @if(isset($data) && $data->type == "percent") selected @endif value="percent">درصدی</option>
                </select>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="amount"
                    label="مقدار(تومان یا درصد)"
                    :validations="['numberCms','requiredCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="count"
                    label="تعداد کد مورد نیاز "
                    :validations="['numberCms','requiredCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="basket_minimum_price"
                    label="حداقل مبلغ سبد خرید برای استفاده"
                    :validations="['numberCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="max_usage_per_user"
                    label="حداکثر استفاده هر کاربر"
                    :validations="['numberCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-6 col-sm-6 p-2">
            <label for="userSelect" class="d-block">
                کاربر
            </label>
            <select
                id="userSelect"
                name="user_id"
                class="form-control select2"
                data-placeholder="انتخاب کاربر"
                data-allow-clear="true"
            >
                <option></option>
                @if(isset($user))
                    @foreach($user as $row)
                        <option
                            @selected(old('user_id', $data->user_id ?? null) == $row->id)
                            value="{{ $row->id }}">
                            {{ $row->full_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-xxl-6 col-sm-6 p-2">
            <div class="form-group">
                <label>نوع اعمال تخفیف</label>
                <span class="text-danger">*</span>
                <select name="pay_type" id="payTypeSelect" class="form-select">
                    <option value="">انتخاب کنید</option>
                    <option value="product">اعمال بر اساس محصولات</option>
                    <option value="category">اعمال بر اساس دسته‌بندی</option>
                    <option value="brand">اعمال بر اساس برند</option>
                </select>
            </div>
        </div>
        <div class="col-xxl-12 col-sm-6 p-2 d-none" id="categoryBox">
            <label for="categories" class="d-block">
                دسته بندی ها
            </label>
            <span class="text-danger">*توجه بفرمایید این کد تخفیف با توجه به محصولات دسته بندی انتخاب شده اعمال خواهد شد؛ نه مشخصه و بازه قیمت.</span>
            <select
                id="categoriesSelect"
                multiple
                name="product_categories[]"
                class="boot-select limited-select2 text-start"
                style="width: 100%;"
                data-limit-selected="7"
            >
                @if(isset($product_categories))
                    @foreach($product_categories as $key=>$cat)
                        <option
                            @selected(old('product_categories') ? in_array($cat->id, old('product_categories')) : in_array($cat['id'], $selected_categories))
                            {{ count($cat->children) > 0 ? 'disabled' : '' }}
                            value="{{ $cat['id'] }}">
                            {{ $cat['title'] }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-xxl-12 col-sm-6 p-2 d-none" id="brandBox">
            <label for="brandsSelect" class="d-block p-1 pb-3">
                برند ها
            </label>
            <select
                id="brandsSelect"
                multiple
                name="brands[]"
                class="boot-select limited-select2 text-start"
                style="width: 100%;"
                data-limit-selected="7"
            >
                @if(isset($brands))
                    @foreach($brands as $key=>$brand)
                        <option
                            @selected(old('brands') ? in_array($brand->id, old('brands')) : in_array($brand->id, $selected_brands)) value="{{ $brand->id }}" >
                            {{ $brand->title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="first_purchase"
                    label="مخصوص خرید اول "
                    :valueData="@$data"
                    :value="0"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="with_discount"
                    label="قابل اعمال روی محصولات تخفیف دار  "
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
@push('styles')
    <link href="{{ asset("assets/admin/css/select2.min.css") }}" rel="stylesheet"/>
    <style>
        /* حالت انتخاب‌شده در dropdown - بنفش ملایم */
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #7367f0; /* بنفش خیلی ملایم */
            color: #4b0082; /* بنفش تیره برای متن */
        }

        /* گزینه‌ای که موقع هاور هایلایت میشه */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7367f0; /* بنفش روشن تر */
            color: white;
        }

        /* در حالت multiple، تگ‌های انتخاب‌شده */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #7367f0;
            border: 1px solid #a855f7;
            color: white;
            font-family: 'iransans', sans-serif;
        }

        /* آیکون فلش */
        .select2-container--default .select2-selection__arrow {
            height: 40px;
            right: 10px;
        }

        /* فونت برای تمام بخش‌های select2، شامل placeholder */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection__rendered,
        .select2-container--default .select2-results__option {
            font-family: 'iransans', sans-serif !important;
            font-size: 14px;
        }
        /* حالت انتخاب‌شده در dropdown - بنفش ملایم */
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #7367f0; /* بنفش خیلی ملایم */
            color: #4b0082; /* بنفش تیره برای متن */
        }

        /* گزینه‌ای که موقع هاور هایلایت میشه */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7367f0; /* بنفش روشن تر */
            color: white;
        }

        /* در حالت multiple، تگ‌های انتخاب‌شده */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #7367f0;
            border: 1px solid #a855f7;
            color: white;
            font-family: 'iransans', sans-serif;
        }

        /* آیکون فلش */
        .select2-container--default .select2-selection__arrow {
            height: 40px;
            right: 10px;
        }
        /* فونت برای تمام بخش‌های select2، شامل placeholder */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection__rendered,
        .select2-container--default .select2-results__option {
            font-family: 'iransans', sans-serif !important;
            font-size: 14px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset("assets/admin/js/select2.min.js") }}"></script>
    <script>
        $(document).ready(function () {
            $('#userSelect').select2({
                dir: "rtl",
                theme: "bootstrap-5", // مهم برای هماهنگی با فرم بالا
                placeholder: $('#userSelect').data('placeholder') || 'انتخاب کنید',
                allowClear: true,
                language: {
                    noResults: function () {
                        return "موردی یافت نشد";
                    }
                }
            });
            $('#categoriesSelect').select2({
                dir: "rtl",
                theme: "bootstrap-5", // مهم برای هماهنگی با فرم بالا
                placeholder: $('#categoriesSelect').data('placeholder') || 'انتخاب کنید',
                allowClear: true,
                language: {
                    noResults: function () {
                        return "موردی یافت نشد";
                    }
                }
            });
            $('#productCategoriesSelect').select2({
                dir: "rtl",
                theme: "bootstrap-5", // مهم برای هماهنگی با فرم بالا
                placeholder: $('#productCategoriesSelect').data('placeholder') || 'انتخاب کنید',
                allowClear: true,
                language: {
                    noResults: function () {
                        return "موردی یافت نشد";
                    }
                }
            });
        });


    </script>
    <script>
        $(document).ready(function () {
            function handleLimitSelection($select) {
                const limit = parseInt($select.data('limit-selected') || 5);

                $select.select2({
                    dir: "rtl",
                    placeholder: $select.attr('placeholder') || 'انتخاب کنید',
                    closeOnSelect: false,
                    language: {
                        noResults: function () {
                            return "موردی یافت نشد";
                        }
                    }
                });

                let expanded = false;

                function renderLimitedChoices(showAll = false) {
                    const selectedData = $select.select2('data');
                    const container = $select.next('.select2-container').find('.select2-selection__rendered');
                    container.empty();

                    const itemsToShow = showAll ? selectedData : selectedData.slice(0, limit);

                    itemsToShow.forEach((item) => {
                        const $choice = $(`
                        <li class="select2-selection__choice" title="${item.text}">
                            <span class="select2-selection__choice__remove" role="presentation">×</span>${item.text}
                        </li>
                    `);

                        $choice.find('.select2-selection__choice__remove').on('click', function (e) {
                            e.stopPropagation();
                            const newVals = $select.val().filter(val => val != item.id);
                            $select.val(newVals).trigger('change');
                        });

                        container.append($choice);
                    });

                    if (selectedData.length > limit) {
                        const toggleBtn = $(`
                        <li class="select2-selection__choice" style="cursor:pointer;">
                            ${showAll ? 'بستن' : `+${selectedData.length - limit} مورد دیگر`}
                        </li>
                    `);

                        toggleBtn.on('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            expanded = !expanded;
                            renderLimitedChoices(expanded);
                        });

                        container.append(toggleBtn);
                    }
                }

                $select.on('change', function () {
                    renderLimitedChoices(expanded);
                });

                renderLimitedChoices(false);
            }

            // اجرای تابع روی تمام سلکت‌هایی که data-limit-selected دارند
            $('select[data-limit-selected]').each(function () {
                handleLimitSelection($(this));
            });

            // تابع برای بارگذاری محصولات
            function loadProducts(selectId, loadingId, selectedIds = [], query = '') {
                var page = 1;
                var loading = false;
                var hasMoreData = true;
                var loadedIds = []; // آرایه برای ذخیره IDهای بارگذاری شده
                var isLoadingFirstTime = true; // برای تشخیص اولین بارگذاری

                function fetchData() {
                    if (loading || !hasMoreData) return;
                    loading = true;

                    // نمایش پیام "در حال بارگذاری..." فقط در اولین بار
                    if (isLoadingFirstTime) {
                        $('#' + loadingId).show();
                        isLoadingFirstTime = false;
                    }

                    $.ajax({
                        url: '/admin/product/form-products',
                        method: 'GET',
                        data: { query: query, page: page },
                        success: function (data) {
                            var optlist = $('#' + selectId);

                            // اگر صفحه اول است، لیست را خالی کن
                            if (page === 1) {
                                optlist.empty();
                                loadedIds = [];
                            }

                            // اگر داده‌ای دریافت شده است
                            if (data.data.length > 0) {
                                // اضافه کردن آیتم‌های جدید به لیست
                                $.each(data.data, function (index, product) {
                                    if (!loadedIds.includes(product.id)) {
                                        optlist.append($('<option/>').attr('value', product.id).text(product.title));
                                        loadedIds.push(product.id); // اضافه کردن ID به لیست بارگذاری شده
                                    }
                                });

                                // به‌روزرسانی selectpicker
                                optlist.selectpicker('refresh');

                                // اگر selectedIds وجود دارد، آیتم‌ها را انتخاب کن
                                if (selectedIds.length > 0 && page === 1) {
                                    selectedIds = selectedIds.map(String);
                                    optlist.selectpicker('val', selectedIds);
                                }

                                // افزایش شماره صفحه برای درخواست بعدی
                                page++;
                            }

                            // بررسی وجود داده‌های بیشتر
                            hasMoreData = data.has_more_pages;

                            loading = false;

                            // اگر داده‌های بیشتری وجود ندارد، پیام "در حال بارگذاری..." را مخفی کن
                            if (!hasMoreData) {
                                $('#' + loadingId).hide();
                            }

                            // اگر داده‌های بیشتری وجود دارد، درخواست بعدی را ارسال کن
                            if (hasMoreData) {
                                setTimeout(fetchData, 1000); // تاخیر ۱ ثانیه قبل از درخواست بعدی
                            }
                        },
                        error: function () {
                            $('#' + loadingId).hide();
                            loading = false;
                            alert('خطا در بارگذاری داده‌ها');
                        }
                    });
                }

                // شروع بارگذاری داده‌ها
                fetchData();
            }

            // دریافت IDهای محصولات مرتبط و مکمل از data-* attributes
            var relatedIds = JSON.parse($('#relatedProductsSelect').attr('data-related-ids') || '[]');
            var complementIds = JSON.parse($('#completeProductsSelect').attr('data-complement-ids') || '[]');

            // بارگذاری داده‌ها برای select اول (محصولات مرتبط)
            loadProducts('relatedProductsSelect', 'loading1', relatedIds);

            // بارگذاری داده‌ها برای select دوم (محصولات مکمل)
            loadProducts('completeProductsSelect', 'loading2', complementIds);
        });
    </script>
    <script>
        $(document).ready(function () {
            function hideAllBoxes() {
                $('#productBox, #categoryBox, #brandBox').addClass('d-none');
            }
            $('#payTypeSelect').on('change', function () {
                hideAllBoxes();

                switch ($(this).val()) {
                    case 'category':
                        $('#categoryBox').removeClass('d-none');
                        break;
                    case 'brand':
                        $('#brandBox').removeClass('d-none');
                        break;
                }
            });
            @if(old('pay_type') || isset($data->pay_type))
                $('#payTypeSelect').val('{{ old('pay_type', $data->pay_type ?? '') }}').trigger('change');
            @endif
        });
    </script>
    <script>
        $('form').on('submit', function () {
            // پاک کردن name قبلی
            $('#categoriesSelect').removeAttr('name');
            $('#brandsSelect').removeAttr('name');

            // حذف hidden های قبلی
            $('input[name="product_categories[]"], input[name="brands[]"]').remove();

            const type = $('#payTypeSelect').val();

            if (type === 'category') {
                $('#categoriesSelect').attr('name', 'product_categories[]');

                // حتی اگه چیزی انتخاب نشده باشه
                if (!$('#categoriesSelect').val() || $('#categoriesSelect').val().length === 0) {
                    $(this).append('<input type="hidden" name="product_categories[]" value="">');
                }
            }

            if (type === 'brand') {
                $('#brandsSelect').attr('name', 'brands[]');

                // حتی اگه چیزی انتخاب نشده باشه
                if (!$('#brandsSelect').val() || $('#brandsSelect').val().length === 0) {
                    $(this).append('<input type="hidden" name="brands[]" value="">');
                }
            }
        });
    </script>

@endpush

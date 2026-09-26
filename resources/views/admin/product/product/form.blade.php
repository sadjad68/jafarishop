<div class="container-fluid">
    <div class="card-block row w-100 m-0">
        <div class="col-xxl-6 col-sm-6 p-2">
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
        <div class="col-xxl-6 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="url"
                    label="آدرس url"
                    :validations="['requiredCms','urlCms']"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-12 col-sm-12 p-2">
            <label for="categories" class="d-block">
                دسته بندی ها
                <span class="text-danger">*</span>
            </label>
            <select
                id="categoriesSelect"
                requiredCms
                multiple
                name="categories[]"
                class="boot-select limited-select2 text-start"
                style="width: 100%;"
                data-limit-selected="5"
            >
                @if(isset($categories))
                    @foreach($categories as $key=>$cat)
                        <option
                            @selected(old('categories') ? in_array($cat->id, old('categories')) : in_array($cat['id'], $selected_categories))
                            value="{{ $cat['id'] }}">
                            {{ $cat['title'] }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        @if(isset($data) && count($data->variants) > 0)
            <div role="alert" class="alert alert-info d-block">
                برای تغییر موجودی و قیمت محصولات با متغییر به
                <a href="{{route('admin.product-variant.index',['id'=>$data->id])}}">صفحه مربوطه</a>
                مراجعه کنید
            </div>
        @endif
        <div role="alert" class="alert alert-info d-block">
            توجه داشته باشید اگر موجودی محصول برابر با صفر تعیین شده باشد، عبارت <b>ناموجود</b> نمایش داده خواهد شد
            اما اگر محصول موجودی داشته باشد و قیمت آن صفر باشد، در بخش‌های مختلف سایت با عبارت <b>تماس بگیرید</b> نمایش
            داده می‌شود.
        </div>

        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="price"
                    label="قیمت(تومان)"
                    :validations="['numberCms']"
                    :properties="isset($data) && count($data->variants)  > 0 ? ['readonly'] : []"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="discounted_price"
                    label="قیمت با تخفیف(تومان)"
                    :validations="['numberCms']"
                    :properties="isset($data) && count($data->variants) > 0 ? ['readonly'] : []"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <div class="form-group">
                <x-cms-input
                    name="stock"
                    label="موجودی"
                    :validations="['numberCms']"
                    :properties="isset($data) && count($data->variants) > 0 ? ['readonly'] : []"
                    type="text"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            @if(isset($data) && count($data->variants) > 0)
                <div role="alert" class="alert alert-info d-block p-2 mt-4">
                    برای تغییر وزن محصولات با متغییر به
                    <a href="{{route('admin.product-variant.index',['id'=>$data->id])}}">صفحه مربوطه</a>
                    مراجعه کنید
                </div>
            @else
                <div class="form-group">
                    <x-cms-input
                        name="weight"
                        label="وزن (گرم)"
                        :validations="['numberCms']"
                        {{--                        ,'requiredCms'--}}
                        type="text"
                        :valueData="@$data"
                    />
                </div>
            @endif
        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <x-cms-select
                name="brand_id"
                label=" برند"
                :options="$brand"
                optionValue="id"
                optionLabel="title"
                :searchable="true"
                :clearable="true"
                :selectedOption="isset($data->brand_id) ? $data->brand_id : null"
            />
        </div>
        @if(@App\Library\SiteHelper::getInformation()['site_name'] == "khodadadgallery")
            <div class="col-xxl-4 col-sm-6 p-2">
                <x-cms-select
                    name="price_formula"
                    label="محاسبه قیمت بر حسب فرمول"
                    :options="Config::get('site.price_formula')"
                    :searchable="false"
                    :selectedOption="isset($data->price_formula) ? $data->price_formula : null"
                />
            </div>
        @endif
        <div class="col-xxl-6 col-sm-6 p-2">
            <label for="relatedProductsSelect">
                محصولات مرتبط
            </label>
            <select
                id="relatedProductsSelect"
                name="products[]"
                multiple
                class="form-control limited-select2"
                data-live-search="true"
                data-related-ids="{{ json_encode($related_products) }}"
                data-limit-selected="5"
            >
                <!-- Options will be loaded here -->
            </select>
            <div id="loading1" style="display:none;">در حال بارگذاری...</div>
        </div>

        <div class="col-xxl-6 col-sm-6 p-2">
            <label for="completeProductsSelect">
                محصولات مکمل
            </label>
            <select
                id="completeProductsSelect"
                name="complement[]"
                multiple
                class="form-control limited-select2"
                data-live-search="true"
                data-complement-ids="{{ json_encode($complement_products) }}"
                data-limit-selected="1"
            >
                <!-- Options will be loaded here -->
            </select>
            <div id="loading2" style="display:none;">در حال بارگذاری...</div>
        </div>

        <div class="col-xxl-12 col-sm-12 p-2">
            <label for="tags">
                تگ ها
            </label>
            <select
                id="tags"
                multiple
                class="w-100 boot-select selectpicker"
                data-live-search="true"
                placeholder="تگ را انتخاب کنید"
                name="tags[]"
            >
                @foreach($tags as $key=>$row)
                    @php
                        $selected = isset($data) ? @$data->tags()->find($row->id) : false;
                    @endphp
                    <option value="{{$row['id']}}"
                        @selected(old('tags') ? in_array($row->id,old('tags')) : $selected)
                        {{ (isset($data) && @$data->tags()->find($row->id)) ? 'selected' : '' }}
                    >
                        {{ $row['title'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 p-2">
            <div class="form-group">
                <x-cms-ck-editor
                    name="description"
                    label="توضیحات"
                    type="text"
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
{{--        <div class="col-lg-3 col-sm-6 col-12 p-2">--}}
{{--            <div class="form-group">--}}
{{--                <x-cms-check-box--}}
{{--                    name="unstable_price"--}}
{{--                    label="نوسان قیمت دارد"--}}
{{--                    :valueData="@$data"--}}
{{--                />--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-lg-3 col-sm-6 col-12">
            @include('admin.components.show-first-page',['section'=>'product'])
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
    </style>
@endpush
@push('scripts')
    <script src="{{ asset("assets/admin/js/select2.min.js") }}"></script>
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
                            ${showAll ? 'بستن' : `+ ${selectedData.length - limit} مورد دیگر`}
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
                        data: {query: query, page: page},
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
@endpush



@push('styles')
    <style>
        .bootstrap-select .dropdown-menu {
            transform: translate(0, -40px) !important;
            left: 0 !important;
            right: 0 !important;
        }
    </style>
@endpush
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
            <label for="categoriesSelect" class="d-block">
                دسته والد
            </label>
            <select
                id="categoriesSelect"
                name="parent_id"
                class="boot-select text-start"
                style="width: 100%;"
                data-placeholder="انتخاب دسته والد"
            >
                {{-- گزینه پیش‌فرض قابل حذف --}}
                <option value=" " {{ old('parent_id', $data->parent_id ?? null) === null ? 'selected' : '' }}>
                    دسته والد
                </option>

                @if(isset($product_categories))
                    @foreach($product_categories as $category)
                        <option
                            value="{{ $category['id'] }}"
                            @if(isset($data) && $category['id'] == $data['id']) disabled @endif
                            @if(old('parent_id') == $category['id'] || (isset($data) && $category['id'] == $data->parent_id))
                                selected
                            @endif
                        >
                            {{ $category['title'] }}
                        </option>
                    @endforeach
                @endif
            </select>


        </div>
        <div class="col-xxl-4 col-sm-6 p-2">
            <x-cms-image-input
                name="image"
                label="تصویر (سایز : w300 * h300 )"
                :imageSrc="(isset($data) && $data->image) ? $data->getImage() : null"
                :deletable="true"
                :deleteUrl="'model='.\App\Modules\Product\Entities\ProductCategory::class.'&id='.@$data['id']"
                width="300"
                height="300"
                cropper="1"
            />
        </div>
        @include('admin.product.product-category.blocks.specifications')
        <div class="col-12 p-2" id="priceRangeWrapper" style="display: none;">
            <div class="row">
                <div class="col-xxl-6 col-sm-6 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="min_price"
                            label="شروع قیمت (تومان)"
                            type="text"
                            :validations="['numberCms']"
                            :valueData="@$data"
                            min="0"
                        />
                    </div>
                </div>
                <div class="col-xxl-6 col-sm-6 p-2">
                    <div class="form-group">
                        <x-cms-input
                            name="max_price"
                            label="پایان قیمت (تومان)"
                            type="text"
                            :validations="['numberCms']"
                            :valueData="@$data"
                            min="0"
                        />
                    </div>
                </div>
            </div>
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
                    label="نمایش در منو"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="show_in_site"
                    label="نمایش در سایت"
                    :valueData="@$data"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 p-2">
            <div class="form-group">
                <x-cms-check-box
                    name="have_price_range"
                    label="دارای بازه قیمتی"
                    :valueData="@$data"
                    value="0"
                />
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            @include('admin.components.show-first-page',['section'=>'product_category'])
        </div>
        <div class="col-xl-3 col-md-4 col-sm-6 col-12 ms-auto p-2">
            @include('admin._layouts.blocks.utils.page-getter')
            <button type="submit" id="submitFormCms" class="btn btn-custom rounded-custom w-25">
                ذخیره
            </button>
        </div>
    </div>
</div>
@push('styles')
    <link href="{{ asset("assets/admin/css/select2.min.css") }}" rel="stylesheet"/>
    <style>
        .select2-selection__arrow {
            left: 20px !important;
        }

        .select2-selection__clear {
            position: relative;
            z-index: 10;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset("assets/admin/js/select2.min.js") }}"></script>
    <script>
        $(document).ready(function () {
            $('#categoriesSelect').select2({
                dir: "rtl",
                placeholder: "انتخاب دسته والد",
                allowClear: true, // برای فعال شدن دکمه ×
                closeOnSelect: false,
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

            function togglePriceRange() {
                if ($('input[name="have_price_range"]').is(':checked')) {
                    $('#priceRangeWrapper').slideDown();
                } else {
                    $('#priceRangeWrapper').slideUp();
                }
            }

            // اجرا در لود صفحه (برای حالت ویرایش)
            togglePriceRange();

            // تغییر وضعیت با کلیک
            $('input[name="have_price_range"]').on('change', function () {
                togglePriceRange();
            });

        });
    </script>

    @include('admin._layouts.blocks.utils.ckeditor-scripts')
@endpush

<div class="col-xl-3 col-lg-4 p-0 d-lg-block d-none plp-aside">
    <div class="sidebar">
        <div class="filter">
            <p class="d-flex align-items-center mb-2 font-md">
                <i class="bi bi-funnel d-flex fs-5 me-1"></i>
                فیلتر محصولات
            </p>

            @include('pages.product-list.components.filtered-item')
            <div class="accordion accordion-flush mt-2" id="accordionFlushExample">
                @if($max_price > 0)
                    <div class="accordion-item">
                        <p class="accordion-header">
                            <button class="accordion-button dynamic-color" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="true"
                                    aria-controls="flush-collapseOne">
                                فیلتر قیمت
                            </button>
                        </p>
                        <div id="flush-collapseOne" class="accordion-collapse collapse show"
                             data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row w-100 mx-0 mt-0">
                                    <div class="col-12 p-1">
                                        <div class="d-flex align-items-center">
                                            <small class="m-0 color-title">
                                                از
                                            </small>
                                            <input name="min" type="text" min="{{@$min_price}}" max="{{@$max_price}}"
                                                   oninput="validity.valid||(value='{{@$min_price}}');" id="min_price"
                                                   readonly
                                                   class="price-range-field font-num shadow-none form-control border-0 bgcolor3 fs-custom2 rounded-custom1 text-center">
                                            <small class="m-0 color-title">
                                                تومان
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12 p-1">
                                        <div class="d-flex align-items-center">
                                            <small class="m-0 color-title">
                                                تا
                                            </small>
                                            <input name="max" type="text" min="{{@$min_price}}" max="{{@$max_price}}"
                                                   oninput="validity.valid||(value='{{@$max_price}}');" id="max_price"
                                                   readonly
                                                   class="price-range-field font-num shadow-none form-control border-0 bgcolor3 fs-custom2 rounded-custom1 text-center">
                                            <small class="m-0 color-title">
                                                تومان
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12 p-1 mt-2 my-auto">
                                        <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                                    </div>
                                    <button class="btn btn-filter-desktop py-2 btn-submit-filter btn-sm mt-2" type="button"
                                            @click="getProductAxios('priceFilterDesktop')">
                                        اعمال
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sort-form mb-2">
                        <ul class="p-0 m-0">
                            <li class="list-unstyled">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" value="1" type="checkbox" role="switch" id="discounted"
                                           v-model="discountedPrice">
                                    <label class="form-check-label small" for="discounted">محصولات تخفیف دار</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                @endif
                    <div class="sort-form mb-2">
                        <ul class="p-0 m-0">
                            <li class="list-unstyled">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" value="1" type="checkbox" role="switch" id="available"
                                           v-model="availableProducts">
                                    <label class="form-check-label small" for="available">محصولات موجود</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                @if(count($children) > 0)
                    <div class="accordion-item">
                        <p class="accordion-header">
                            <button class="accordion-button collapsed dynamic-color" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapsefour" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                دسته بندی
                            </button>
                        </p>
                        <div id="flush-collapsefour" class="accordion-collapse collapse"
                             data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body categories">
                                @include('pages.product-list.components.category')
                            </div>
                        </div>
                    </div>
                @endif
                <template v-for="filter in filters">
                <div class="accordion-item" v-if="filter.children && filter.children.length" :key="filter.id">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed dynamic-color" type="button" data-bs-toggle="collapse"
                                :data-bs-target="'#flush-collapseTwo'+filter.id" aria-expanded="false"
                                :aria-controls="'flush-collapseTwo'+filter.id">
                            @{{ filter.title }}
                        </button>
                    </p>
                    <div :id="'flush-collapseTwo'+filter.id" class="accordion-collapse collapse"
                         data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-2">
                            <div class="search position-relative mb-2" v-if="filter.children.length > 1">
                                <input type="search" v-model="searchFilter" class="form-control form-control-sm"
                                       :placeholder="'جستجو در ' + filter.title">
                                <button type="button"
                                        class="btn btn-search bg-transparent p-2 shadow-none border-0 position-absolute top-0 bottom-0 end-0">
                                    <i class="bi bi-search d-flex"></i>
                                </button>
                            </div>
                            <ul :class="['p-0 m-0 px-2', { 'd-flex align-items-center justify-content-center gap-3 flex-wrap mt-3': filter.is_color == 1 }]">
                                <li v-if="searchedChildren(filter)" class="list-unstyled m-0"
                                    v-for="child in searchedChildren(filter)" :key="child.id">
                                    <div class="form-check color-filter" v-if="filter.is_color == 1">
                                        <input class="form-check-input colorInput" type="checkbox" :value="child.id"
                                               :id="'colorFilter'+child.id" v-model="selectedFilters">
                                        <label class="form-check-label colorLabel"
                                               :style="{ backgroundColor: child.color_code ? child.color_code : '#000000' }"
                                               :for="'colorFilter'+child.id"></label>
                                        <p class="font-small font-re m-0 text-center">@{{ child.title }}</p>
                                    </div>
                                    <div class="form-check" v-else>
                                        <input class="form-check-input" type="checkbox" :value="child.id"
                                               :id="'flexCheckDefaults'+child.id" v-model="selectedFilters">
                                        <label class="form-check-label small" :for="'flexCheckDefaults'+child.id">@{{
                                            child.title }}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                </template>
                <div class="accordion-item" v-if="brands && brands.length">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed dynamic-color" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseThree" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                            برند ها
                        </button>
                    </p>
                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                         data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-2">
                            <div class="search position-relative mb-2" v-if="brands.length > 1">
                                <input type="search" v-model="searchBrand" class="form-control form-control-sm"
                                       placeholder="جستجوی نام برند">
                                <button type="button"
                                        class="btn btn-search bg-transparent p-2 shadow-none border-0 position-absolute top-0 bottom-0 end-0">
                                    <i class="bi bi-search d-flex"></i>
                                </button>
                            </div>
                            <ul class="p-0 m-0 px-2">

                                <li class="list-unstyled" v-for="(brand,index) in searchedBrands">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :value="brand.id"
                                               v-model="selectedBrands" :id="'flexCheckDefault'+index">
                                        <label class="form-check-label small" :for="'flexCheckDefault'+index">
                                            @{{ brand.title }}
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

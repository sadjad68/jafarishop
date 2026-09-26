<form method="GET" action="{{route('search.detail')}}" class="position-relative site-header__search-form" id="search-vue" :class="{ 'is-search-open': searchInput.length > 2 }">
    <input type="hidden" name="search_form" value="1">
    <input type="text" name="search" id="input" @input="searchResult" v-model="searchInput" autocomplete="off" class="form-control shadow-none border-0 font-re" placeholder="جستجوی محصول، برند یا دسته..." minlength="3" role="combobox" aria-autocomplete="list" :aria-expanded="searchInput.length > 2 ? 'true' : 'false'">
    <button type="submit" class="btn border-0 bg-transparent shadow-none position-absolute top-0 bottom-0 start-0" id="search" aria-label="جستجو">
        <i class="bi bi-search d-flex site-header__search-icon" aria-hidden="true"></i>
    </button>
    <div v-if="searchInput.length > 2" v-cloak>
        <div class="suggust-search" v-if="noResults == false">
            <div class="search-suggest__header">
                <span class="search-suggest__header-label">نتایج برای</span>
                <strong class="search-suggest__header-term" v-text="searchInput"></strong>
            </div>
            <div class="p-0 m-0" v-if="searchLoading == true">
                <div class="search-suggest__loading d-flex align-items-center justify-content-center py-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">در حال جستجو</span>
                    </div>
                </div>
            </div>
            <div class="search-suggest__body" v-else>
                <div class="search-suggest__block" v-if="searchedProducts.length > 0">
                    <div class="search-suggest__heading">
                        <span class="search-suggest__heading-text">محصولات</span>
                        <span class="search-suggest__heading-count" v-text="searchedProducts.length"></span>
                    </div>
                    <ul class="search-suggest__products suggest-ul-pro px-0 mx-0 mt-0 mb-0">
                        <li class="suggest-items-pro" v-for="searchedProduct in searchedProducts">
                            <a :href="searchedProduct.url" class="search-suggest__product suggest-link">
                                <span class="search-suggest__product-media">
                                    <img :src="searchedProduct.image" :alt="searchedProduct.title" class="suggest-link-img">
                                </span>
                                <span class="search-suggest__product-body">
                                    <span class="suggest-link-name" v-html="colorResult(searchedProduct.title)"></span>
                                </span>
                                <i class="bi bi-chevron-left search-suggest__product-go" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="search-suggest__grid">
                    <div class="search-suggest__block" v-if="searchedBrands.length > 0">
                        <div class="search-suggest__heading">
                            <span class="search-suggest__heading-text">برندها</span>
                        </div>
                        <div class="search-suggest__chips">
                            <a v-for="searchedBrand in searchedBrands" :href="searchedBrand.url" class="search-suggest__chip" v-html="colorResult(searchedBrand.title)"></a>
                        </div>
                    </div>

                    <div class="search-suggest__block" v-if="searchedProductCategories.length > 0">
                        <div class="search-suggest__heading">
                            <span class="search-suggest__heading-text">دسته‌بندی‌ها</span>
                        </div>
                        <div class="search-suggest__chips">
                            <a v-for="searchedCategory in searchedProductCategories" :href="searchedCategory.url" class="search-suggest__chip" v-html="colorResult(searchedCategory.title)"></a>
                        </div>
                    </div>

                    <div class="search-suggest__block" v-if="searchedBlogs.length > 0">
                        <div class="search-suggest__heading">
                            <span class="search-suggest__heading-text">بلاگ‌ها</span>
                        </div>
                        <ul class="search-suggest__links">
                            <li v-for="searchedBlog in searchedBlogs">
                                <a :href="searchedBlog.url" class="search-suggest__link" v-html="colorResult(searchedBlog.title)"></a>
                            </li>
                        </ul>
                    </div>

                    <div class="search-suggest__block" v-if="searchedServices.length > 0">
                        <div class="search-suggest__heading">
                            <span class="search-suggest__heading-text">خدمات</span>
                        </div>
                        <div class="search-suggest__chips">
                            <a v-for="searchedService in searchedServices" :href="searchedService.url" class="search-suggest__chip" v-html="colorResult(searchedService.title)"></a>
                        </div>
                    </div>

                    <div class="search-suggest__block" v-if="searchedPortfolios.length > 0">
                        <div class="search-suggest__heading">
                            <span class="search-suggest__heading-text">نمونه‌کارها</span>
                        </div>
                        <ul class="search-suggest__links">
                            <li v-for="searchedPortfolio in searchedPortfolios">
                                <a :href="searchedPortfolio.url" class="search-suggest__link" v-html="colorResult(searchedPortfolio.title)"></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <button type="submit" class="btn search-suggest__view-all w-100 font-th">
                    مشاهده همه نتایج
                    <i class="bi bi-arrow-left-short d-flex"></i>
                </button>
            </div>
        </div>
        <div class="suggust-search suggust-search--empty" v-if="noResults == true && searchInput.length > 0">
            <div class="search-suggest__empty">
                <p class="search-suggest__empty-title">نتیجه‌ای نیست</p>
                <p class="search-suggest__empty-desc">عبارت دیگری برای «<span class="search-highlight" v-text="searchInput"></span>» بنویسید</p>
            </div>
        </div>
    </div>
</form>
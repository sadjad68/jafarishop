<form method="GET" action="{{ route('search.detail') }}"
      class="t1-mobile-search__form"
      id="search-vue"
      :class="{ 'is-open': searchInput.length > 2 }">
    <input type="hidden" name="search_form" value="1">
    <input type="text"
           name="search"
           id="input"
           @input="searchResult"
           v-model="searchInput"
           autocomplete="off"
           class="t1-mobile-search__input"
           placeholder="جستجو..."
           minlength="3"
           aria-label="جستجو">
    <button type="submit" class="t1-mobile-search__submit" aria-label="جستجو">
        <i class="bi bi-search" aria-hidden="true"></i>
    </button>
    <div class="t1-mobile-search__suggest" v-if="searchInput.length > 2" v-cloak>
        <div class="suggust-search" v-if="noResults == false">
            <div class="p-0 m-0" v-if="searchLoading == true">
                <div class="d-flex align-items-center justify-content-center py-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">در حال جستجو</span>
                    </div>
                </div>
            </div>
            <div class="t1-search__results" v-else>
                <div class="t1-search__block" v-if="searchedProducts.length > 0">
                    <p class="t1-search__heading">محصولات</p>
                    <ul class="px-0 mx-0 mt-0 mb-0 d-flex w-100 suggest-ul-pro">
                        <li class="py-1 suggest-items-pro" v-for="searchedProduct in searchedProducts">
                            <a :href="searchedProduct.url" class="text-secondary suggest-link">
                                <img :src="searchedProduct.image" :alt="searchedProduct.title" class="suggest-link-img w-100">
                                <div class="mt-2 suggest-link-name mb-1 px-1" v-html="colorResult(searchedProduct.title)"></div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="t1-search__block" v-if="searchedBrands.length > 0">
                    <p class="t1-search__heading">برندها</p>
                    <ul class="p-0 m-0">
                        <li v-for="searchedBrand in searchedBrands">
                            <a :href="searchedBrand.url" class="t1-search__hit" v-html="colorResult(searchedBrand.title)"></a>
                        </li>
                    </ul>
                </div>
                <div class="t1-search__block" v-if="searchedProductCategories.length > 0">
                    <p class="t1-search__heading">دسته‌بندی‌ها</p>
                    <ul class="p-0 m-0">
                        <li v-for="searchedCategory in searchedProductCategories">
                            <a :href="searchedCategory.url" class="t1-search__hit" v-html="colorResult(searchedCategory.title)"></a>
                        </li>
                    </ul>
                </div>
                <div class="t1-search__block" v-if="searchedBlogs.length > 0">
                    <p class="t1-search__heading">بلاگ‌ها</p>
                    <ul class="p-0 m-0">
                        <li v-for="searchedBlog in searchedBlogs">
                            <a :href="searchedBlog.url" class="t1-search__hit" v-html="colorResult(searchedBlog.title)"></a>
                        </li>
                    </ul>
                </div>
                <div class="t1-search__block" v-if="searchedServices.length > 0">
                    <p class="t1-search__heading">خدمات</p>
                    <ul class="p-0 m-0">
                        <li v-for="searchedService in searchedServices">
                            <a :href="searchedService.url" class="t1-search__hit" v-html="colorResult(searchedService.title)"></a>
                        </li>
                    </ul>
                </div>
                <div class="t1-search__block" v-if="searchedPortfolios.length > 0">
                    <p class="t1-search__heading">نمونه‌کارها</p>
                    <ul class="p-0 m-0">
                        <li v-for="searchedPortfolio in searchedPortfolios">
                            <a :href="searchedPortfolio.url" class="t1-search__hit" v-html="colorResult(searchedPortfolio.title)"></a>
                        </li>
                    </ul>
                </div>
                <button type="submit" class="t1-search__more">مشاهده همه</button>
            </div>
        </div>
        <div class="suggust-search" v-if="noResults == true && searchInput.length > 0">
            <p class="t1-search__empty mb-0">موردی یافت نشد</p>
        </div>
    </div>
</form>

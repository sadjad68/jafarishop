<script src="{{asset('assets/site/js/tpl-vue-paginate.js')}}"></script>
<script>
    Vue.component('paginate', VuejsPaginate)
    Vue.directive('scroll', {
        inserted: function (el, binding) {
            let f = function (evt) {
                if (binding.value(evt, el)) {
                    window.removeEventListener('scroll', f)
                }
            }
            window.addEventListener('scroll', f)
        }
    });
    new Vue({
        el: '#app',
        data: {
            categories: @json($categories) ? @json($categories) : [],
            filters: @json($filters) ? @json($filters) : [],
            brand_id: '{{$brand['id']}}',
            selectedCategories: [],
            discountedPrice: [],
            availableProducts: [],
            selectedFilters: [],
            products: [],
            searchCategory: "",
            searchFilter: "",
            productTitle: "",
            page: 1,
            currentPage: 1,
            pageCount: 1,
            stopCall: false,
            isFilter: false,
            loading: false,
            filterLoading: false,
            titleLoading: false,
            scrollable: true,
            category_id: "",
            filter_id: "",
            discounted_price: 0,
            stock: 0,
            sortBy: "",
            sort_by: "",
            priceRange : "",
            minPrice : "",
            maxPrice : "",
        },
        methods: {

            async handlePageClick(pageNumber) {
                this.page = pageNumber;
                this.deleteLaravel();
                this.products =  [];
                await this.getProductAxios();
                const filteredElement = document.getElementById('base');
                if (filteredElement) {
                    filteredElement.scrollIntoView({ behavior: 'smooth' });
                }
            },
            async getProductAxios(val) {
                this.loading = true;

                if(val === "priceFilterDesktop"){
                    this.products = [];
                    this.page = 1;
                    this.deleteLaravel();
                    this.priceRange = document.getElementById('min_price').value+'_'+document.getElementById('max_price').value;
                    this.minPrice = document.getElementById('min_price').value;
                    this.maxPrice = document.getElementById('max_price').value;
                    this.updateUrlParams();
                }
                if(val === "priceFilterMobile"){
                    this.products = [];
                    this.page = 1;
                    this.deleteLaravel();
                    this.priceRange = document.getElementById('min_price_mobile').value+'_'+document.getElementById('max_price_mobile').value
                    this.minPrice = document.getElementById('min_price_mobile').value;
                    this.maxPrice = document.getElementById('max_price_mobile').value;
                    this.updateUrlParams();
                }
                // if(this.isFilter === true){
                //     this.products = [];
                // }
                if(this.priceRange){
                    const parts = this.priceRange.split('_');
                    this.minPrice = parseInt(parts[0], 10);
                    this.maxPrice = parseInt(parts[1], 10);
                    // this.page = 1;
                }

                try {
                    const response = await axios.post('{{$core_url.'api/v1/product-brand-vue'}}', {
                        page: this.page,
                        category_id: this.category_id,
                        product_title: this.productTitle,
                        brand_id: this.brand_id,
                        filter_id: this.filter_id,
                        discounted_price: this.discounted_price,
                        stock: this.stock,
                        sort_by: this.sort_by,
                        min_price: parseInt(this.minPrice),
                        max_price: parseInt(this.maxPrice),
                        price_range: this.priceRange,
                    });
                    this.products = [...this.products, ...response.data.data.products];
                    this.lastPage = response.data.data.lastPage;
                    this.pageCount = response.data.data.lastPage;
                    this.currentPage = response.data.data.currentPage;
                    this.stopCall = this.page >= response.data.data.lastPage;
                    this.loading = false;
                    this.titleLoading = false;

                } catch (error) {
                    console.error('Error fetching products:', error);
                }
            },

            scroll(e) {
                const { target } = e;
                const container = target.scrollingElement;
                const scrollHeight = container.scrollHeight;
                const scrollTop = container.scrollTop;
                const clientHeight = container.clientHeight;
                const scrollMePleaseDiv = document.getElementById('scrollMePlease');
                const divTop = scrollMePleaseDiv.offsetTop;
                const divHeight = scrollMePleaseDiv.offsetHeight;
                if(this.page < 6){
                    if (scrollTop + clientHeight >= divTop && scrollTop <= divTop + divHeight && !this.loading && !this.stopCall
                        && this.scrollable) {
                        this.page++;
                        this.getProductAxios();
                    }
                    if(this.page === 6)
                    {
                        this.scrollable = false;
                    }                }
            },
            deleteLaravel() {
                const element = document.getElementById('laravel-base');
                if (element) {
                    element.remove();
                }
            },
            nameCategory(val) {
                return this.categories.find(x => x.id === val).title;
            },
            nameFilter(val) {
                for (const filter of this.filters) {
                    const child = filter.children.find(child => child.id === val);
                    if (child) {
                        return child.title;
                    }
                }
                return '';
            },
            removeItem(name, key = null) {
                if (typeof this[name] === 'object') {
                    this[name].splice(this[name].indexOf(key), 1);
                } else if (typeof this[name] === 'number' || typeof this[name] === 'boolean') {
                    this[name] = 0;
                } else if (typeof this[name] === 'string') {
                    this[name] = "";
                }
                this.updateUrlParams();
            },
            updateUrlParams() {
                const queryString = new URLSearchParams();
                if (this.selectedCategories.length > 0) {
                    queryString.set('selectedCategories', this.selectedCategories.join('_'));
                }
                if (this.selectedFilters.length > 0) {
                    queryString.set('selectedFilters', this.selectedFilters.join('_'));
                }
                if (this.discountedPrice.length > 0) {
                    queryString.set('discountedPrice', this.discountedPrice.join('_'));
                }
                if (this.availableProducts.length > 0) {
                    queryString.set('availableProducts', this.availableProducts.join('_'));
                }
                if (this.sortBy) {
                    queryString.set('sortBy', this.sortBy);
                }
                if (this.priceRange) {
                    queryString.set('priceRange', this.priceRange);
                }

                let newUrl = window.location.pathname;
                const queryStr = queryString.toString();
                if (queryStr) {
                    newUrl += '?' + queryStr;
                }
                const filteredElement = document.getElementById('base');
                if (filteredElement) {
                    filteredElement.scrollIntoView({ behavior: 'smooth' });
                }
                history.pushState({}, '', newUrl);
            },


        },
        mounted() {
            // Initialize selectedCategories from URL params on page load
            const params = new URLSearchParams(window.location.search);
            if (params.has('selectedCategories')) {
                this.selectedCategories = params.get('selectedCategories').split('_').map(v => parseInt(v));
                this.deleteLaravel();
            }
            if (params.has('discountedPrice')) {
                this.discountedPrice = params.get('discountedPrice').split('_').map(v => parseInt(v));
                this.deleteLaravel();
            }
            if (params.has('availableProducts')) {
                this.availableProducts = params.get('availableProducts').split('_').map(v => parseInt(v));
                this.deleteLaravel();
            }
            if (params.has('sortBy')) {
                this.sortBy = params.get('sortBy');
                this.deleteLaravel();
            }
            if (params.has('selectedFilters')) {
                this.selectedFilters = params.get('selectedFilters').split('_').map(v => parseInt(v));
                this.deleteLaravel();
            }
            if (params.has('priceRange')) {

                this.priceRange = params.get('priceRange');
                this.minPrice = this.priceRange.split('_')[0];
                this.maxPrice = this.priceRange.split('_')[1];
                document.getElementById('min_price').setAttribute('value', this.minPrice);
                document.getElementById('min_price_mobile').setAttribute('value', this.minPrice);
                document.getElementById('max_price').setAttribute('value', this.maxPrice);
                document.getElementById('max_price_mobile').setAttribute('value', this.maxPrice);
                this.deleteLaravel();
            }
            if (params.has('selectedFilters')) {
                this.selectedFilters = params.get('selectedFilters').split('_').map(v => parseInt(v));
                this.deleteLaravel();
            }
        },
        watch: {
            selectedCategories: {
                handler: async function (newVal) {
                    this.filterLoading = true;
                    this.products = [];
                    this.deleteLaravel();
                    this.category_id = newVal;
                    this.page = 1;
                    await this.getProductAxios();
                    this.filterLoading = false;
                    this.updateUrlParams();
                },
                deep: true
            },
            discountedPrice: {
                handler: async function () {
                    this.filterLoading = true;
                    this.products = [];
                    this.deleteLaravel();
                    this.page = 1;
                    this.discounted_price = this.discounted_price === 1 ? 0 : 1;
                    await this.getProductAxios();
                    this.filterLoading = false;
                    this.updateUrlParams();
                },
                deep: true
            },
            availableProducts: {
                handler: async function () {
                    this.filterLoading = true;
                    this.products = [];
                    this.deleteLaravel();
                    this.page = 1;
                    this.stock = this.stock === 1 ? 0 : 1;
                    await this.getProductAxios();
                    this.filterLoading = false;
                    this.updateUrlParams();

                },
                deep: true
            },
            selectedFilters: {
                handler: async function (newVal) {
                    this.filterLoading = true;
                    this.products = [];
                    this.deleteLaravel();
                    this.filter_id = newVal;
                    this.page = 1;
                    await this.getProductAxios();
                    this.filterLoading = false;
                    this.updateUrlParams();
                },
                deep: true
            },
            sortBy: {
                handler: async function (sortMe) {
                    this.filterLoading = true;
                    this.products = [];
                    if (sortMe) {
                        this.deleteLaravel();
                        this.page = 1;
                        this.sort_by = sortMe;
                        await this.getProductAxios();
                    } else {
                        this.page = 1;
                        this.sort_by = "";
                        this.discounted_price = 0;
                        this.stock = 0;
                        await this.getProductAxios();
                    }
                    this.filterLoading = false;
                    this.updateUrlParams();

                },
                deep: true
            },
            async productTitle(){
                clearTimeout(this.debounceTimer);
                this.debounceTimer = window.setTimeout(async () => {
                    this.filterLoading = true;
                    this.isFilter = true;
                    this.products = [];
                    this.deleteLaravel();
                    this.products = [];
                    this.titleLoading = true;
                    this.page = 1;
                    await this.getProductAxios();
                    this.filterLoading = false;
                }, 500);


            },
        },
        computed: {
            searchedCategories: function () {
                if (this.searchCategory !== '') {

                    return this.categories.filter((category) => {
                        return category.title.toLowerCase().includes(this.searchCategory.toLowerCase());
                    });
                } else {
                    return this.categories;
                }
            },
            searchedChildren() {
                return (filter) => {
                    if (this.searchFilter !== '') {
                        return filter.children.filter(child => child.title.toLowerCase().includes(this.searchFilter.toLowerCase()));
                    } else {
                        return filter.children;
                    }
                };
            },

        },

    });
</script>

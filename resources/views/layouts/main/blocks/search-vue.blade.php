<script>
    new Vue({
        el: '#search-vue',
        data: function () {
            return {
                //search
                searchLoading: false,
                searchInput: '',
                search: '',
                searchedServices: [],
                searchedBlogs: [],
                searchedPortfolios: [],
                searchedProducts: [],
                searchedProductCategories: [],
                searchedBrands: [],
                noResults: false,
            }
        },
        methods: {
            //search Start

            async searchResult() {
                this.searchInput = event.target.value;
                if (this.searchInput.length > 2) {
                    this.searchLoading = true;
                    const response = await axios.get('{{$core_url.'api/v1/search-api'}}?search=' + this.searchInput);
                    this.searchedServices = response.data.data.searched_services;
                    this.searchedBlogs = response.data.data.searched_blogs;
                    this.searchedPortfolios = response.data.data.searched_portfolios;
                    this.searchedProducts = response.data.data.searched_products;
                    this.searchedProductCategories = response.data.data.searched_categories;
                    this.searchedBrands = response.data.data.searched_brands;
                    if (this.searchedServices.length + this.searchedBlogs.length + this.searchedPortfolios.length
                        + this.searchedProductCategories.length + this.searchedBrands.length
                        + this.searchedProducts.length == 0) {
                        this.noResults = true;
                    } else {
                        this.noResults = false;
                    }
                    this.searchLoading = false;
                } else {
                    this.searchedServices = [];
                    this.searchedBlogs = [];
                    this.searchedPortfolios = [];
                    this.searchedProducts = [];
                    this.searchedProductCategories = [];
                    this.searchedBrands = [];
                }


            },
            escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            },
            colorResult(sentence) {
                if (sentence == null) {
                    return sentence;
                }
                const query = (this.searchInput || '').trim();
                const text = String(sentence);
                if (!query) {
                    return this.escapeHtml(text);
                }
                const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(escapedQuery, 'gi');
                let result = '';
                let lastIndex = 0;
                let match;

                while ((match = regex.exec(text)) !== null) {
                    if (match.index > lastIndex) {
                        result += this.escapeHtml(text.slice(lastIndex, match.index));
                    }
                    result += '<span class="search-highlight">' + this.escapeHtml(match[0]) + '</span>';
                    lastIndex = match.index + match[0].length;
                }

                if (lastIndex < text.length) {
                    result += this.escapeHtml(text.slice(lastIndex));
                }

                return result || this.escapeHtml(text);
            },
        }
    });
</script>

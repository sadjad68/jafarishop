<script>
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
        el: '#samples',
        data: function () {
            return {
                samples: [],
                selectedService: '',
                loading: false,
                page: 1,
                stopCall: false,
                isFilter: false,
                lastPage: 1
            }
        },
        methods: {
            selectService(id) {
                if (this.selectedService === id) {
                    return;
                }
                this.selectedService = id;
                this.page = 1;
                this.samples = [];
                this.stopCall = false;
                if (id) {
                    this.isFilter = true;
                    this.getSampleAxios();
                    return;
                }
                this.isFilter = false;
            },
            scroll(e) {
                const { target } = e;
                const container = target.scrollingElement;
                const scrollHeight = container.scrollHeight;
                const scrollTop = container.scrollTop;
                const clientHeight = container.clientHeight;
                const scrollMePleaseDiv = document.getElementById('scrollMePlease');
                if (!scrollMePleaseDiv) {
                    return;
                }
                const divTop = scrollMePleaseDiv.offsetTop;
                const divHeight = scrollMePleaseDiv.offsetHeight;
                if (scrollTop + clientHeight >= divTop && scrollTop <= divTop + divHeight && !this.loading && !this.stopCall) {
                    this.page++;
                    this.getSampleAxios();
                }
            },
            async getSampleAxios() {
                if (this.loading) {
                    return;
                }
                this.loading = true;
                const payload = { page: this.page };
                if (this.selectedService) {
                    payload.service_id = this.selectedService;
                }
                try {
                    const response = await axios.post('{{$core_url.'api/v1/portfolio-vue'}}', payload);
                    const pageSamples = response.data.data.samples || [];
                    if (this.page === 1) {
                        this.samples = pageSamples;
                    } else {
                        this.samples = [...this.samples, ...pageSamples];
                    }
                    this.lastPage = response.data.data.lastPage || response.data.data.pageCount || 1;
                    this.stopCall = this.page >= this.lastPage;
                } catch (e) {
                    this.stopCall = true;
                } finally {
                    this.loading = false;
                }
            }
        }
    });
</script>

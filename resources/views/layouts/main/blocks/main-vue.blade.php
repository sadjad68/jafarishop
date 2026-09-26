<script>
    var {{$element_id}}_nav = new Vue({
        el: '#{{$element_id}}',
        data: function () {
            return {
                branches: {!! $branches ? Js::from($branches->toArray()) : '[]' !!},
                mainBranch: {!! $main_branch ?  Js::from($main_branch->toArray()) : 'null' !!},
                basketItemCount: 0,
                loadingBasket: true
            };
        },
        methods: {
            async getBasketItemCount() {
                try {
                    const response = await axios.post('{{ route('basket.count') }}');
                    this.basketItemCount = response.data.basketCount;
                } catch (error) {
                    console.error("Error fetching basket count:", error);
                } finally {
                    this.loadingBasket = false;
                }
            }
        },
        mounted() {
            this.getBasketItemCount();
        }
    });
</script>

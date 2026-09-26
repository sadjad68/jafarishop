<script type="application/javascript">
    new Vue({
        el: '#app',
        data: {
            items: [],
            totalQuantity: 0,
            itemListLoading : false,
            priceLoading : false,
            itemLoading : false,
            numberValue: 1,
            finalPriceSum: 0,
            priceSum: 0,
            priceDiscount: 0,
        },
        methods: {
            buildCartTrackingItems() {
                if (typeof window.EcommerceTracking === 'undefined') {
                    return [];
                }
                return (this.items || []).map((line) => window.EcommerceTracking.buildItemFromBasketLine(line));
            },
            pushCartEvent(eventName, extra) {
                if (typeof window.EcommerceTracking === 'undefined') {
                    return;
                }
                const items = this.buildCartTrackingItems();
                if (!items.length) {
                    return;
                }
                window.EcommerceTracking.pushEcommerceEvent(
                    eventName,
                    window.EcommerceTracking.eventPayload(items, extra || {})
                );
            },
            async getItems(load  = true) {
                this.itemListLoading = load;
                this.priceLoading = true;

                try {
                    const response = await axios.get('{{ route('basket.cart-items') }}');
                    this.items = response.data.basketCollection.data ? response.data.basketCollection.data : [];
                    this.totalQuantity = this.items.reduce((sum, item) => sum + parseFloat(item.quantity) || 0, 0);
                } catch (error) {
                    console.error("Error fetching items: ", error);
                } finally {
                    this.itemListLoading = false;
                }
                await this.getPrice(load);
            },
            async removeCart(itemId) {
                let removedLine = null;
                try {
                    removedLine = this.items.find((row) => row.id === itemId);
                    await axios.post('{{ route('basket.cart-item-remove') }}', {
                        item_id: itemId
                    });
                    if (removedLine && typeof window.EcommerceTracking !== 'undefined') {
                        const item = window.EcommerceTracking.buildItemFromBasketLine(removedLine);
                        window.EcommerceTracking.pushEcommerceEvent(
                            'remove_from_cart',
                            window.EcommerceTracking.eventPayload([item])
                        );
                    }
                    await this.getItems();
                    menu_nav.getBasketItemCount();
                } catch (error) {
                    console.error("Error removing item:", error);
                }
            },
            //changeQuantity
            increaseValue(index) {
                const checkQuantity = this.items[index].quantity;
                this.items[index].quantity = parseFloat(this.items[index].quantity) + 1;
                this.changeItem(this.items[index],checkQuantity,index);
                menu_nav.getBasketItemCount();
            },
            decreaseValue(index) {
                if (this.items[index].quantity > 1) {
                    const checkQuantity = this.items[index].quantity;
                    this.items[index].quantity = parseFloat(this.items[index].quantity) - 1;
                    this.changeItem(this.items[index],checkQuantity,index);
                    menu_nav.getBasketItemCount();
                }
            },
            async changeItem(item,checkQuantity,index) {
                this.itemLoading = true;
                const response =   await axios.post('{{ route('basket.add') }}', {
                    product_id: item.product_id,
                    product_variant_id: item.variant_id,
                    quantity: item.quantity,
                    cart : true
                });
                this.itemLoading = false;
                if (response.data.success === false && response.data.button == false) {
                    Swal.fire({
                        icon: 'info',
                        text: response.data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    this.$set(this.items, index, {
                        ...item,
                        quantity: checkQuantity,
                    });
                    return false;
                }

                await this.getItems(false);
                menu_nav.getBasketItemCount();

            },
            //price
            async getPrice(load  = true) {
                this.priceLoading = load;

                try {
                    const response = await axios.get('{{ route('basket.list-price') }}');
                    console.log(response.data.final_price_sum);
                    this.finalPriceSum = parseInt(response.data.final_price_sum) !== 0
                        ? parseInt(response.data.final_price_sum).toLocaleString() + ' تومان '
                        : 0;
                    this.priceSum = parseInt(response.data.price_sum) !== 0
                        ? parseInt(response.data.price_sum).toLocaleString() + ' تومان '
                        : 0;
                    this.priceDiscount = parseInt(response.data.price_discount) !== 0
                        ? parseInt(response.data.price_discount).toLocaleString() + ' تومان '
                        : 0;

                } catch (error) {
                    console.error("Error fetching items: ", error);
                } finally {
                    this.priceLoading = false;
                }
            },


        },
        async mounted() {
            await this.getItems();
            this.pushCartEvent('view_cart');
        },
        watch: {},
        computed: {},

    });
</script>

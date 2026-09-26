<script type="application/javascript">
    new Vue({
        el: '#app',
        data: {
            items: [],
            loadingList : false,
            loadingShipment : false,
            loadingAddress : false,
            priceLoading : true,
            finalPriceSum: 0,
            priceSum: 0,
            priceDiscount: 0,
            priceCart: 0,
            priceShipping: '',
            locations:[],
            selectedState: '',
            selectedCity: '',
            states: [],
            searchStateText: '',
            cities: [],
            searchCityText: "",
            receiptorMobile: '',
            receiptorName: '',
            postalCodeForm: '',
            address: '',
            addressId: '',
            defaultAddressId: '{{@$basket->address_id}}',
            defaultShippingMethodId: '{{@$basket->shipping_method_id}}',
            shippingMethods: [],
            IsPostalCodeRequired: {{@$settings['require_postal_code']}}
        },
        methods: {
            async fetchCartItemsForTracking() {
                const response = await axios.get('{{ route('basket.cart-items') }}');
                return response.data.basketCollection.data ? response.data.basketCollection.data : [];
            },
            async trackCheckoutFunnel(eventName, extra, sessionKey) {
                if (typeof window.EcommerceTracking === 'undefined') {
                    return;
                }
                const lines = await this.fetchCartItemsForTracking();
                const items = lines.map((line) => window.EcommerceTracking.buildItemFromBasketLine(line));
                if (!items.length) {
                    return;
                }
                const payload = window.EcommerceTracking.eventPayload(items, extra || {});
                const onceKey = sessionKey || (eventName === 'begin_checkout' ? 'begin_checkout' : null);
                if (onceKey) {
                    window.EcommerceTracking.pushOncePerSession(onceKey, eventName, payload);
                } else {
                    window.EcommerceTracking.pushEcommerceEvent(eventName, payload);
                }
            },
            resolveShippingTier() {
                const method = (this.shippingMethods || []).find(
                    (row) => String(row.id) === String(this.defaultShippingMethodId)
                );
                return method && method.title ? method.title : '';
            },
            async persistShippingMethod() {
                if (!this.defaultShippingMethodId) {
                    return false;
                }
                const response = await axios.post('{{ route('basket.set-shipments') }}', {
                    shipping_method_id: this.defaultShippingMethodId,
                });
                return response.data && response.data.success === true;
            },
            async trackAddShippingInfo() {
                const shipping_tier = this.resolveShippingTier();
                if (!shipping_tier) {
                    return;
                }
                const sessionKey = 'add_shipping_info_' + this.defaultShippingMethodId;
                await this.trackCheckoutFunnel('add_shipping_info', { shipping_tier: shipping_tier }, sessionKey);
            },
            async syncShippingSelectionAndTrack() {
                if (!this.defaultShippingMethodId || !this.shippingMethods.length) {
                    return;
                }
                const exists = this.shippingMethods.some(
                    (row) => String(row.id) === String(this.defaultShippingMethodId)
                );
                if (!exists) {
                    return;
                }
                try {
                    const saved = await this.persistShippingMethod();
                    if (!saved) {
                        return;
                    }
                    await this.getPrice(false);
                    await this.trackAddShippingInfo();
                } catch (error) {
                    console.error('add_shipping_info tracking failed:', error);
                }
            },
            async goToPayment() {
                if (this.defaultShippingMethodId && this.shippingMethods.length) {
                    await this.syncShippingSelectionAndTrack();
                }
                window.location.href = '{{ route('basket.payment') }}';
            },
            async getAddresses() {
                this.loadingList = true;
                this.loadingShipment = false;
                const response = await axios.get('{{route('basket.address-list')}}');
                this.locations = response.data.addresses.data;
                if(this.locations.length === 1){
                this.defaultAddressId = this.locations[0].id;
                }
                this.loadingList = false
            },
            async getSates() {
                const response = await axios.get('{{route('basket.states')}}');
                this.states = response.data.states;
            },
            async getCities() {
                this.loadingAddress = true;
                const response = await axios.post('{{route('basket.cities')}}');
                this.cities = response.data.cities;
                this.loadingAddress = false;
            },
            searchState(search) {
                this.searchStateText = search;
            },
            searchCity(search) {
                this.searchCityText = search;
            },
            async setCities() {
                this.loadingAddress = true;
                const response = await axios.post('{{ route('basket.cities') }}', {
                    state_id: this.selectedState,
                });
                this.cities = response.data.cities;
                this.loadingAddress = false;
            },
            async editAddress(id) {
                this.loadingAddress = true;
                try {
                    const response = await axios.post('{{ route('basket.address-edit') }}', {
                        address_id: id,
                    });
                    const stateId = response.data.address.state_id;
                    this.selectedState = this.states.find(state => state.id === stateId) || null;
                    await this.setCities();
                    const cityId = response.data.address.city_id;
                    this.selectedCity = this.cities.find(city => city.id === cityId) || null;
                    this.address = response.data.address.address;
                    this.postalCodeForm = response.data.address.postal_code;
                    this.receiptorName = response.data.address.receiptor_full_name;
                    this.receiptorMobile = response.data.address.receiptor_mobile;
                    this.addressId = response.data.address.id;
                } catch (error) {
                    console.error('خطا در دریافت اطلاعات آدرس:', error);
                } finally {
                    this.loadingAddress = false;
                }
            },
            async getShippingMethod() {
                this.loadingShipment = true;
                try {
                    const response = await axios.post('{{ route('basket.shipments') }}', {
                        address_id: this.defaultAddressId,
                    });
                    this.shippingMethods = response.data.shipping_methods || [];
                    await this.syncShippingSelectionAndTrack();
                } catch (error) {
                    console.error('Error loading shipping methods:', error);
                } finally {
                    this.loadingShipment = false;
                }
            },
            async setShippingMethod() {
                try {
                    const saved = await this.persistShippingMethod();
                    if (!saved) {
                        return;
                    }
                    await this.getPrice(false);
                    await this.trackAddShippingInfo();
                } catch (error) {
                    console.error('setShippingMethod failed:', error);
                }
            },
            getShippingPriceText(shippingMethod) {
                return shippingMethod.price == 0 ? 'ارسال رایگان' : this.formatPrice(shippingMethod.price) + ' تومان';
            },
            formatPrice(value) {
                if (!value) return '';
                return new Intl.NumberFormat().format(value);
            },
            //price
            async getPrice(load  = true) {
                this.priceLoading = load;
                try {
                    const response = await axios.get('{{ route('basket.address-price') }}');
                    console.log(response.data.price_shipping);
                    this.finalPriceSum = parseInt(response.data.final_price_sum) !== 0
                        ? parseInt(response.data.final_price_sum).toLocaleString() + ' تومان '
                        : 0;
                    this.priceSum = parseInt(response.data.price_sum) !== 0
                        ? parseInt(response.data.price_sum).toLocaleString() + ' تومان '
                        : 0;
                    this.priceDiscount = parseInt(response.data.price_discount) !== 0
                        ? parseInt(response.data.price_discount).toLocaleString() + ' تومان '
                        : 0;
                    if (response.data.price_shipping === '' || response.data.price_shipping === null || response.data.price_shipping === undefined) {
                        this.priceShipping = 'نامشخص';
                    }  else if (response.data.freight_balance === 1) {
                        this.priceShipping = 'پس کرایه';
                    }
                    else if (response.data.price_shipping === 'not_custom') {
                        this.priceShipping = 'طبق تعرفه شرکت انتخابی';
                    }
                    else {
                        let shippingPrice = parseInt(response.data.price_shipping);
                        this.priceShipping = shippingPrice !== 0 && !isNaN(shippingPrice)
                            ? shippingPrice.toLocaleString() + ' تومان '
                            : 'ارسال رایگان';
                    }
                    this.priceCart = parseInt(response.data.price_cart) !== 0
                        ? parseInt(response.data.price_cart).toLocaleString() + ' تومان '
                        : 0;

                } catch (error) {
                    if(error.response?.data?.error){
                        this.defaultShippingMethodId = '';
                        this.priceShipping = '';
                        swal("", error.response?.data?.error, "error", {
                            button: "باشه",
                        });
                    }
                    else{
                        console.error('خطا:', error.response?.data?.error || error.message);
                    }
                } finally {
                    this.priceLoading = false;
                }
            },
            checkForm(e, edit) {
                e.preventDefault();
                this.receiptorMobile = this.receiptorMobile.trim();
                if (this.receiptorName == null || this.receiptorName === '') {
                    swal("", "نام تحویل گیرنده اجباری است.", "error", {
                        button: "باشه",
                    });
                    return false;
                }
                if (this.receiptorMobile == null || this.receiptorMobile === "") {
                    swal("", "شماره تحویل گیرنده اجباری است.", "error", {
                        button: "باشه",
                    });
                    return false;
                }
                if (this.receiptorMobile.length !== 11) {
                    swal("", "شماره گیرنده حتما باید ۱۱ رقم باشد", "error", {
                        button: "باشه",
                    });
                    return false;
                }
                if (this.selectedState === null || this.selectedState === '') {
                    swal("", "استان اجباری است.", "error", {
                        button: "باشه",
                    });
                    return false;
                }
                if (this.selectedCity == null || this.selectedCity === '') {
                    swal("", "شهر اجباری است.", "error", {
                        button: "باشه",
                    });
                    return false;
                }
                if (this.address == null || this.address === '') {
                    swal("", "آدرس اجباری است.", "error", {
                        button: "باشه",
                    });
                    return false;
                }
                 if(this.IsPostalCodeRequired == 1) {
                    if (this.postalCodeForm != null && this.postalCodeForm.trim() !== "") {
                        this.postalCodeForm = this.postalCodeForm.trim();
                        if (this.postalCodeForm.length !== 10) {
                            swal("", "کد پستی حتما باید ۱۰ رقم باشد", "error", {
                                button: "باشه",
                            });

                        } else {
                            if (edit === true) {
                                document.getElementById("editForm").submit()

                            } else {
                                document.getElementById("addForm").submit()
                            }

                        }
                    } else {
                        swal("", "کد پستی الزامی است", "error", {
                            button: "باشه",
                        });
                    }
                }else{
                    if (edit === true) {
                        document.getElementById("editForm").submit()

                    } else {
                        document.getElementById("addForm").submit()
                    }
                }
            },
            isNumeric(value) {
                return /^[0-9۰-۹]+$/.test(value);
            },
            isPersianLetters(value) {
                return /^[\u0600-\u06FF\s]+$/.test(value);
            },
            validateMobile() {
                if (this.receiptorMobile && !this.isNumeric(this.receiptorMobile)) {
                    swal("", "در فیلد شماره تلفن فقط عدد مجاز است", "error", { button: "باشه" });
                    this.receiptorMobile = this.receiptorMobile.replace(/[^0-9۰-۹]/g, '');
                }
            },
            validatePostalCode() {
                if (this.postalCodeForm && !this.isNumeric(this.postalCodeForm)) {
                    swal("", "در فیلد کد پستی فقط عدد مجاز است", "error", { button: "باشه" });
                    this.postalCodeForm = this.postalCodeForm.replace(/[^0-9۰-۹]/g, '');
                }
            },
            validateName() {
                if (this.receiptorName && !this.isPersianLetters(this.receiptorName)) {
                    swal("", "در فیلد نام فقط حروف فارسی مجاز است", "error", { button: "باشه" });
                    this.receiptorName = this.receiptorName.replace(/[^آ-ی\s]/g, '');
                }
            },
            resetForm() {
                document.querySelectorAll('.collapse.show').forEach(el => {
                    const collapseInstance = bootstrap.Collapse.getInstance(el)
                    if (collapseInstance) {
                        collapseInstance.hide()
                    } else {
                        new bootstrap.Collapse(el, { toggle: false }).hide()
                    }
                })
                this.selectedState = ''
                this.selectedCity = ''
                this.receiptorMobile = ''
                this.receiptorName = ''
                this.postalCodeForm = ''
                this.address = ''
            }
        },
        async mounted() {
            await this.getAddresses();
            await this.getSates();
            if(this.defaultAddressId){
                await this.editAddress(this.defaultAddressId);
                await this.getShippingMethod();
            }
            await this.getPrice(true);
            await this.trackCheckoutFunnel('begin_checkout');
        },
        watch: {},
        computed: {
            filteredStates() {
                if (!this.searchStateText) {
                    return this.states;
                }
                return this.states.filter(state =>
                    state.name.includes(this.searchStateText)
                );
            },
            filteredCities() {
                if (!this.searchCityText) {
                    return this.cities;
                }
                return this.cities.filter(city =>
                    city.name.includes(this.searchCityText)
                );
            }
        },

    });
</script>

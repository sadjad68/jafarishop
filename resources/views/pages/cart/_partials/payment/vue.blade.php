<script type="application/javascript">
    new Vue({
        el: '#app',
        data: {
            discountCode : '{{@$basket->discount->title}}',
            discountId : '{{@$basket->discount->title}}',
            finalPriceSum: 0,
            taxPrice: 0,
            tax: 0,
            priceSum: 0,
            priceDiscount: 0,
            priceCart: 0,
            priceCartNumber: 0,
            priceShipping: '',
            discountAmount: 0,
            gatewayTariff: 0,
            priceLoading : false,
            cartDepositNumber : {{intval(@$settings['cart_deposit'])}},
            depositPrice : "{{number_format(@$settings['deposit_price']) .' تومان '}}",
            snappData: [],
            banks: @json($banks),
            selectedBankId: null,
            filteredBanks: [],
            cartTermsAcceptanceEnabled: {{ (int) (@$settings['cart_terms_acceptance_enabled'] ?? 0) === 1 ? 'true' : 'false' }},
            termsAccepted: false,

        },
        methods: {
            async fetchCartItemsForTracking() {
                const response = await axios.get('{{ route('basket.cart-items') }}');
                return response.data.basketCollection.data ? response.data.basketCollection.data : [];
            },
            async trackPaymentInfo(bankId) {
                if (typeof window.EcommerceTracking === 'undefined' || !bankId) {
                    return;
                }
                const bank = (this.banks || []).find((row) => String(row.id) === String(bankId));
                if (!bank) {
                    return;
                }
                const lines = await this.fetchCartItemsForTracking();
                const items = lines.map((line) => window.EcommerceTracking.buildItemFromBasketLine(line));
                if (!items.length) {
                    return;
                }
                const extra = { payment_type: bank.title || bank.bank_type || '' };
                if (this.discountCode) {
                    extra.coupon = this.discountCode;
                }
                window.EcommerceTracking.pushEcommerceEvent(
                    'add_payment_info',
                    window.EcommerceTracking.eventPayload(items, extra)
                );
            },
            submitPayment(event) {
                if (this.cartTermsAcceptanceEnabled && !this.termsAccepted) {
                    Swal.fire({
                        icon: 'error',
                        text: 'ابتدا قوانین را مطالعه کنید و تیک قبول را بزنید.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    return false;
                }
                event.target.submit();
            },
            async addDiscount() {
                try {
                    const response =await axios.post('{{ route('basket.add-discount') }}', {
                        discount_code: this.discountCode
                    });
                    console.log(response.data.success)
                    if(response.data.success === true){
                        Swal.fire({
                            icon: 'info',
                            text: response.data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        this.discountId = response.data.discount_id;

                    }else{
                        Swal.fire({
                            icon: 'info',
                            text: response.data.message,
                            showConfirmButton: true,
                            confirmButtonText: 'باشه',
                            timer: 5000
                        });
                        this.discountId = '';
                        this.discountCode = '';
                    }
                    await this.getPrice();
                    return false;

                } catch (error) {
                    console.error("Error removing item:", error);
                }

            },
            async deleteDiscount() {
                const response = await axios.get('{{ route('basket.delete-discount') }}');

                Swal.fire({
                    icon: 'info',
                    text: 'کد تخفیف با موفقیت حذف شد',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                this.discountId = '';
                this.discountCode = '';
                await this.getPrice(true);
                return false;


            },
            warnRequired(fieldName) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    text:  fieldName + ' اجباری است.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                return false;
            },
            async copyToClipboard(text, label) {
                const value = String(text || '').trim();
                if (!value) {
                    return;
                }
                const normalized = value.replace(/\s+/g, '');
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(normalized);
                    } else {
                        const textarea = document.createElement('textarea');
                        textarea.value = normalized;
                        textarea.setAttribute('readonly', '');
                        textarea.style.position = 'absolute';
                        textarea.style.left = '-9999px';
                        document.body.appendChild(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textarea);
                    }
                    Swal.fire({
                        icon: 'success',
                        text: (label || 'مقدار') + ' کپی شد',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        text: 'کپی انجام نشد',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            //price
            async getPrice(load  = true) {
                this.priceLoading = load;
                try {
                    const response = await axios.get('{{ route('basket.order-price') }}', {
                        params: {
                            bank_id: this.selectedBankId
                        }
                    });
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
                    } else if (response.data.freight_balance === 1) {
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
                    this.priceCartNumber = parseInt(response.data.price_cart) !== 0
                        ? parseInt(response.data.price_cart)
                        : 0;
                    this.discountAmount = parseInt(response.data.discount_amount) !== 0
                        ? parseInt(response.data.discount_amount).toLocaleString() + ' تومان '
                        : 0;
                    this.taxPrice = parseInt(response.data.tax_value) !== 0
                        ? parseInt(response.data.tax_value).toLocaleString() + ' تومان '
                        : 0;
                    this.tax = parseInt(response.data.tax) !== 0
                        ? parseInt(response.data.tax).toLocaleString() +'٪'
                        : 0;
                    this.gatewayTariff = parseInt(response.data.gateway_tariff) || 0;
                    this.snappData = response.data.snapp_data;
                    console.log(this.snappData)

                } catch (error) {
                    if (error.response?.data?.error) {
                        this.defaultShippingMethodId = '';
                        this.priceShipping = '';
                        swal("", error.response.data.error, "error", {
                            button: "باشه",
                        }).then(() => {
                            location.reload();
                        });
                    }
                    else{
                        console.error('خطا:', error.response?.data?.error || error.message);

                    }
                } finally {
                    this.priceLoading = false;
                }

            },
            setFilteredBanks() {
                console.log('----this.snappData---');
                console.log(this.snappData);
                let banks = [];
                banks = this.banks;
                // فیلتر بانک اسنپ فقط در صورت snapp_show یا ادمین
                banks = banks.filter(bank => {
                    if (bank.bank_type === "snappay") { // فقط بانک اسنپ
                        return this.snappData.snapp_show;
                    }
                    return true; // بقیه بانک‌ها بدون تغییر نمایش داده می‌شوند
                });

                this.filteredBanks = banks;
            }
        },
        async mounted() {
            await this.getPrice(true);
            this.setFilteredBanks();
            if (this.filteredBanks.length > 0) {
                this.selectedBankId = this.filteredBanks[0].id;
            }
        },
        watch: {
            selectedBankId(newId, oldId) {
                this.trackPaymentInfo(newId);
                if (newId && newId !== oldId) {
                    this.getPrice(true);
                }
            },
            filteredBanks: {
                immediate: true,
                handler(newBanks) {
                    if (newBanks && newBanks.length > 0) {
                        this.selectedBankId = newBanks[0].id;
                    } else {
                        this.selectedBankId = null;
                    }
                }
            }
        },
        computed: {
            selectedCardToCardBank() {
                if (!this.selectedBankId) {
                    return null;
                }
                const bank = (this.filteredBanks || []).find((row) => String(row.id) === String(this.selectedBankId));
                if (!bank || bank.bank_type !== 'cardtocard') {
                    return null;
                }
                let config = {};
                if (typeof bank.config === 'string' && bank.config) {
                    try {
                        config = JSON.parse(bank.config) || {};
                    } catch (e) {
                        config = {};
                    }
                } else if (bank.config && typeof bank.config === 'object') {
                    config = bank.config;
                }
                const expireMinutes = parseInt(
                    bank.reservation_expire_minutes || config.reservation_expire_minutes || 0,
                    10
                );
                return {
                    ...bank,
                    card_number: bank.card_number || config.card_number || '',
                    shaba_number: bank.shaba_number || config.shaba_number || '',
                    account_holder_name: bank.account_holder_name || config.account_holder_name || '',
                    reservation_expire_minutes: expireMinutes > 0 ? expireMinutes : null,
                };
            }
        },

    });
</script>

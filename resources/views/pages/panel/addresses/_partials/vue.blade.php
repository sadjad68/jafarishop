<script type="application/javascript">
    new Vue({
        el: '#app',
        data: {
            items: [],
            loadingList : false,
            loadingShipment : false,
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
            defaultAddressId: '',
            defaultShippingMethodId: '',
            shippingMethods: [],
        },
        methods: {
            async getAddresses() {
                this.loadingList = true;
                this.loadingShipment = false;
                const response = await axios.get('{{route('panel.address-list')}}');
                this.locations = response.data.addresses.data;
                this.loadingList = false
            },
            async getSates() {
                const response = await axios.get('{{route('panel.states')}}');
                this.states = response.data.states;
            },
            async getCities() {
                this.loadingAddress = true;
                const response = await axios.post('{{route('panel.cities')}}');
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
                if (!this.selectedState) {
                    this.cities = [];
                    return;
                }

                this.loadingAddress = true;

                const response = await axios.post('{{ route('panel.cities') }}', {
                    state_id: this.selectedState.id, // نکته مهم
                });

                this.cities = response.data.cities;
                this.loadingAddress = false;
            },
            async editAddress(id) {
                this.loadingAddress = true;

                const response = await axios.post('{{ route('panel.address-edit') }}', {
                    address_id: id,
                });

                const stateId = response.data.address.state_id;
                const cityId = response.data.address.city_id;

                // پیدا کردن آبجکت استان
                this.selectedState = this.states.find(s => s.id == stateId) || null;

                // لود کردن شهرها بعد از انتخاب استان
                await this.setCities();

                // پیدا کردن آبجکت شهر
                this.selectedCity = this.cities.find(c => c.id == cityId) || null;

                // مقداردهی سایر فیلدها
                this.address = response.data.address.address;
                this.postalCodeForm = response.data.address.postal_code;
                this.receiptorName = response.data.address.receiptor_full_name;
                this.receiptorMobile = response.data.address.receiptor_mobile;
                this.addressId = response.data.address.id;

                this.loadingAddress = false;
            },

            checkForm(e, edit) {
                e.preventDefault();
                this.receiptorMobile = this.receiptorMobile.trim();
                if (this.receiptorMobile.length !== 11) {
                    swal("", "شماره گیرنده حتما باید ۱۱ رقم باشد", "error", {
                        button: "باشه",
                    });
                    return false;
                }
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
                    if (edit === true) {
                        document.getElementById("addForm").submit()
                    } else {
                        document.getElementById("editForm").submit()
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
            },
            async confirmDeleteAddress(id) {
                const result = await Swal.fire({
                    title: 'مطمئن هستید؟',
                    text: 'این آدرس حذف می‌شود و قابل بازگشت نیست.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله، حذف شود',
                    cancelButtonText: 'انصراف',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true,
                });

                if (!result.isConfirmed) {
                    return;
                }

                try {
                    await axios.post('{{ route('panel.address-delete') }}', {
                        address_id: id,
                    });
                    await this.getAddresses();
                    Swal.fire({
                        icon: 'success',
                        text: 'آدرس با موفقیت حذف شد',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                } catch (error) {
                    swal('', error.response?.data?.message || 'خطا در حذف آدرس', 'error', {
                        button: 'باشه',
                    });
                }
            }
        },
        async mounted() {
            await this.getAddresses();
            await this.getSates();

            const modalEl = document.getElementById('exampleModal');
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        },
        watch: {},
        computed: {
            filteredStates() {
                if (!this.searchStateText) {
                    return this.states;
                }
                return this.states.filter(s =>
                    s.name.includes(this.searchStateText)
                );
            },
            filteredCities() {
                if (!this.searchCityText) {
                    return this.cities;
                }
                return this.cities.filter(c =>
                    c.name.includes(this.searchCityText)
                );
            }
        }



    });
</script>

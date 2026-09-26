<div class="modal modal-Providing-information fade" id="exampleModal-became-available"
     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="notify-auth-modal">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title fs-5" id="exampleModalLabel">
                    @{{ modalTitle }}
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="handleSubmit">



                    <div v-if="step === 'name'" class="box-input-became-available mb-3">
                        <label class="form-label d-flex justify-content-start">نام و نام خانوادگی:</label>
                        <input v-model="name" type="text" class="form-control"
                               placeholder="علی محمدی" required>
                    </div>

                    <div v-if="step === 'mobile' || step === 'name'" class="box-input-became-available mb-3">
                        <label class="form-label d-flex justify-content-start">شماره همراه:</label>
                        <input v-model="mobile" type="tel" class="form-control"
                               placeholder="۰۹۰۰۰۰۰۰۰۰۰۰" :disabled="step === 'name'" dir="ltr" style="text-align: right;">
                    </div>

                    <div v-if="step === 'otp'" class="box-input-became-available mb-3">
                        <label class="form-label d-flex justify-content-start">کد تایید پیامک شده:</label>
                        <input v-model="code" type="tel" class="form-control text-center"
                               placeholder="- - - -" required maxlength="5">
                    </div>

                    <button type="submit" class="btn-registration d-flex ms-auto mt-3" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        @{{ buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('vue')
    <script>
        var notifyModalApp = new Vue({
            el: '#notify-auth-modal',
            data: {
                step: 'mobile',
                mobile: '',
                name: '',
                code: '',
                loading: false,
                notifyType: null, // 'auction' or 'available'
            },
            computed: {
                modalTitle() {
                    if (this.step === 'otp') return 'تایید شماره همراه';
                    if (this.step === 'name') return 'ثبت نام کاربر جدید';
                    return 'ورود / عضویت';
                },
                buttonText() {
                    if (this.step === 'otp') return 'تایید و ورود';
                    if (this.step === 'name') return 'ثبت نام و دریافت کد';
                    return 'ادامه';
                }
            },
            methods: {
                openModal(type) {
                    this.notifyType = type;
                    this.reset();
                    var myModal = new bootstrap.Modal(document.getElementById('exampleModal-became-available'));
                    myModal.show();
                },
                reset() {
                    this.step = 'mobile';
                    this.mobile = '';
                    this.name = '';
                    this.code = '';
                    this.loading = false;
                },
                async handleSubmit() {
                    if (this.loading) return;

                    if (this.step === 'mobile') {
                        await this.checkUser();
                    } else if (this.step === 'name') {
                        await this.doLoginOrRegister();
                    } else if (this.step === 'otp') {
                        await this.verifyCode();
                    }
                },
                async checkUser() {
                    if (!this.mobile || this.mobile.length < 10) {
                        alert('لطفا شماره همراه معتبر وارد کنید');
                        return;
                    }
                    this.loading = true;
                    try {
                        const res = await axios.get('{{ route("auth.check-user-exists") }}', {
                            params: { mobile: this.mobile }
                        });

                        if (res.data === true) {
                            await this.doLoginOrRegister();
                        } else {
                            this.step = 'name';
                        }
                    } catch (e) {
                        console.error(e);
                        alert('خطا در بررسی وضعیت کاربر');
                    } finally {
                        if (this.step === 'name') this.loading = false;
                    }
                },
                async doLoginOrRegister() {
                    this.loading = true;
                    try {
                        const formData = new FormData();
                        formData.append('mobile', this.mobile);
                        if (this.step === 'name') {
                            formData.append('name', this.name);
                        }

                        await axios.post('{{ route("auth.login") }}', formData);

                        this.step = 'otp';

                    } catch (e) {
                        console.error(e);
                        alert('خطا در ارسال کد تایید');
                    } finally {
                        this.loading = false;
                    }
                },
                async verifyCode() {
                    this.loading = true;
                    try {
                        const formData = new FormData();
                        formData.append('mobile', this.mobile);
                        formData.append('code', this.code);

                        await axios.post('{{ route("auth.confirm-code") }}', formData);

                        const modalEl = document.getElementById('exampleModal-became-available');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if(modalInstance) modalInstance.hide();

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'ورود با موفقیت انجام شد'
                        });

                        //  متد ویو اصلی
                        if (window.mainProductApp && this.notifyType) {
                            window.mainProductApp.isUserLoggedIn = true;
                            window.mainProductApp.submitNotificationRequest(this.notifyType);
                        }

                    } catch (e) {
                        alert('کد وارد شده صحیح نیست');
                    } finally {
                        this.loading = false;
                    }
                }
            },
            mounted() {

                console.log("Modal Vue mounted");
                window.notifyAuthModal = this;

                window.addEventListener('open-auth-modal', (e) => {
                    this.openModal(e.detail);
                });

            }
        });
    </script>
@endpush


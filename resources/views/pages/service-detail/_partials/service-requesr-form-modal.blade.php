<div class="modal fade modal-application-form-detail-servise sk-request-modal" id="exampleModal-application" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <p class="modal-title m-0" id="exampleModalLabel">فرم درخواست خدمات</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <form id="service-request-form" method="post" action="{{route('service.service-request')}}"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($service))
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                    @endif
                    <div class="input-name">
                        <label for="exampleFormControlInput1" class="form-label">نام و نام خانوادگی:</label>
                        <input id="full_name" name="full_name" class="form-control p-2" type="text" placeholder="محمد جمالی"
                               data-required="true"
                               @if(@auth()->check())value="{{\Illuminate\Support\Facades\Auth::user()->full_name}}"
                               @endif
                               aria-label=".form-control-lg example">
                    </div>
                    <div class="input-number py-2">
                        <label for="exampleFormControlInput1" class="form-label">شماره همراه:</label>
                        <input id="phone" name="phone" class="form-control p-2" type="tel" placeholder="۰۹۰۰۰۰۰۰۰۰۰"
                               data-required="true"
                               @if(@auth()->check())value="{{\Illuminate\Support\Facades\Auth::user()->mobile}}" @endif
                               aria-label=".form-control-lg example">
                    </div>
                    <div class="input-file pb-2">
                        <p class="mb-2 text-uplode">فایل‌های خود را انتخاب کنید (بدون محدودیت تعداد):</p>

                        <div class="d-flex align-items-start gap-2 flex-wrap">
                            <label for="formFile" class="upload-button"
                                   style="cursor: pointer; width: 70px; height: 70px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 13px;">
                                <img width="25" height="25" src="{{ asset('assets/site/images/upload-modal.png') }}"
                                     alt="آپلود">
                            </label>
                            <input name="images[]" type="file" id="formFile" multiple accept="image/*"
                                   style="display:none;">

                            <div id="dynamicPreviewContainer" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <div class="textarea-description">
                        <label for="exampleFormControlInput1" class="form-label">توضیحات خود را بنویسید:</label>
                        <textarea name="description" class="form-control p-2" id="exampleFormControlTextarea1"
                                  placeholder="توضیحات درخواست شما...." data-required="true"
                                  rows="3"></textarea>
                    </div>
                    <div class="btn-form-modal pt-3">
                        <button type="submit" class="btn-modal-application-form py-2 px-4">
                            ثبت درخواست
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('service-request-form');
        if (!form) return;

        const fields = [
            {id: 'full_name', type: 'text', label: 'نام و نام خانوادگی'},
            {id: 'phone', type: 'phone', label: 'شماره همراه'},
            {id: 'exampleFormControlTextarea1', type: 'text', label: 'توضیحات'},
            {id: 'formFile', type: 'file', label: 'تصاویر'}
        ];

        function toEnglishDigits(str) {
            if (!str) return str;
            const persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
            const arabicNumbers  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];
            for (let i = 0; i < 10; i++) {
                str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
            }
            return str;
        }

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (!element) return;

            if (field.id === 'phone') {
                element.addEventListener('input', function (e) {
                    let val = e.target.value;
                    let englishVal = toEnglishDigits(val);
                    if (val !== englishVal) {
                        e.target.value = englishVal;
                    }
                    validateField(element, field);
                });
            } else {
                const eventType = field.type === 'file' ? 'change' : 'input';
                element.addEventListener(eventType, function () {
                    validateField(element, field);
                });
            }
        });

        const fileInput = document.getElementById('formFile');
        const previewContainer = document.getElementById('dynamicPreviewContainer');
        let allFiles = new DataTransfer();

        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function () {
                Array.from(this.files).forEach((file) => {
                    allFiles.items.add(file);
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'preview-wrapper';
                        wrapper.style = 'position: relative; width: 70px; height: 70px;';
                        wrapper.innerHTML = '<img src="' + e.target.result + '" alt="" class="preview-img"><button type="button" class="remove-btn" aria-label="حذف تصویر" style="position:absolute;top:-5px;right:-5px;background:red;color:white;border:0;border-radius:50%;width:20px;height:20px;display:flex;justify-content:center;align-items:center;cursor:pointer;font-size:12px;">&times;</button>';
                        wrapper.querySelector('.remove-btn').addEventListener('click', function () {
                            wrapper.remove();
                            const next = new DataTransfer();
                            Array.from(allFiles.files).forEach(function (item) {
                                if (item.name !== file.name) {
                                    next.items.add(item);
                                }
                            });
                            allFiles = next;
                            fileInput.files = allFiles.files;
                        });
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
                this.files = allFiles.files;
            });
        }

        form.addEventListener('submit', function (e) {
            let isValid = true;
            let firstErrorField = null;

            fields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) {
                    const isFieldValid = validateField(element, field);
                    if (!isFieldValid && isValid) {
                        isValid = false;
                        firstErrorField = element;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'خطا',
                        text: 'لطفاً فیلدهای مشخص شده را با دقت تکمیل نمایید.',
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                } else {
                    alert('لطفاً فیلدهای مشخص شده را با دقت تکمیل نمایید.');
                }
            }
        });

        function validateField(element, field) {
            const value = element.value.trim();
            const isRequired = element.getAttribute('data-required') === 'true';
            let errorMessage = '';

            if (isRequired && (value === '' && field.type !== 'file')) {
                errorMessage = `${field.label} الزامی است.`;
            }
            else if (field.type === 'phone' && value !== '') {
                const phoneRegex = /^09[0-9]{9}$/;
                if (!phoneRegex.test(value)) {
                    errorMessage = 'فرمت شماره همراه معتبر نیست (مثال: ۰۹۱۲۳۴۵۶۷۸۹).';
                }
            }
            else if (field.type === 'file') {
                const files = element.files;
                if (files.length > 10) {
                    errorMessage = 'حداکثر ۱۰ تصویر مجاز است.';
                } else {
                    for (let i = 0; i < files.length; i++) {
                        if (files[i].size > 5 * 1024 * 1024) { // 5MB
                            errorMessage = `حجم فایل "${files[i].name}" نباید بیشتر از ۵ مگابایت باشد.`;
                            break;
                        }
                    }
                }
            }
            showError(element, errorMessage);
            return errorMessage === '';
        }

        function showError(element, message) {
            const parent = element.closest('.input-name, .input-number, .textarea-description, .input-file');

            if (message) {
                element.classList.add('is-invalid');
                element.classList.remove('is-valid');

                let errorDiv = parent.querySelector('.invalid-feedback-custom');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback-custom text-danger small mt-1';
                    parent.appendChild(errorDiv);
                }
                errorDiv.innerText = message;
            } else {
                element.classList.remove('is-invalid');
                // فقط اگر فیلد پر شده باشد یا الزامی باشد سبز شود
                if (element.getAttribute('data-required') === 'true' || element.value !== '') {
                    element.classList.add('is-valid');
                }
                const errorDiv = parent.querySelector('.invalid-feedback-custom');
                if (errorDiv) errorDiv.remove();
            }
        }
    });
</script>

<style>
    #phone {
        direction: ltr !important;
        text-align: right !important;
    }

    #phone.is-valid, #phone.is-invalid {
        background-position: right calc(0.375em + 0.1875rem) center !important;
        padding-right: calc(1.5em + 0.75rem) !important;
        padding-left: 0.75rem !important;
    }

    .preview-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<div class="modal fade" id="logoModal" tabindex="-1" aria-labelledby="logoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title fs-5" id="logoModalLabel">راهنمای سایز لوگو</p>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 p-1">
                    <div role="alert" class="alert alert-success d-block m-0" style="white-space: pre-line" >
                        راهنمای انتخاب سایز لوگو

                        🔹 برای لوگوهای مستطیلی :
                        عرض تصویر باید ۳۰۰ پیکسل باشد.
                        ارتفاع محدودیتی ندارد، اما لطفاً از ۵۰۰ پیکسل بیشتر نشود.

                        🔹 برای لوگوهای مربعی :
                        عرض تصویر باید ۲۰۰ پیکسل باشد.
                        ارتفاع محدودیتی ندارد، ولی بهتر است از ۵۰۰ پیکسل بیشتر نباشد.
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function copyToClipboardLegacy(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'متن با موفقیت کپی شد!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }).catch((err) => {
                    console.error('خطا در کپی کردن متن:', err);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'کپی متن انجام نشد.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            } else {
                // پشتیبانی نشدن Clipboard API
                const tempInput = document.createElement('textarea');
                tempInput.style.position = 'absolute';
                tempInput.style.left = '-9999px';
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, tempInput.value.length);
                try {
                    const success = document.execCommand('copy');
                    if (success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'متن با موفقیت کپی شد!',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'کپی متن انجام نشد.',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                } catch (err) {
                    console.error('خطا در کپی کردن متن:', err);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'کپی متن انجام نشد.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
                document.body.removeChild(tempInput);
            }
        }
    </script>


@endpush

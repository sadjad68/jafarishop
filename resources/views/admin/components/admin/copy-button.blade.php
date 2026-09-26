<button class="btn d-flex p-0 align-items-center"
        onclick="copyToClipboardLegacy('{{$url}}')"
   title="کپی لینک"
>
    <span data-bs-toggle="tooltip" data-bs-title="کپی لینک">
            <i class="d-flex bi bi-clipboard color-custom2 fs-5"></i>
    </span>
</button>
@once
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
@endonce
